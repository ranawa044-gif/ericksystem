<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\SpkCriteriaWeight;
use App\Models\AcademicPrediction;
use Illuminate\Support\Facades\DB;

class AcademicDecisionService
{
    public function calculateAll($dosen_id = null)
    {
        $query = Mahasiswa::with(['krs.nilai', 'prestasi', 'pesanKeMahasiswa']);
        if ($dosen_id) {
            $query->where('dosen_wali_id', $dosen_id);
        }
        $mahasiswas = $query->get();

        if ($mahasiswas->isEmpty()) {
            return;
        }

        $weights = SpkCriteriaWeight::all()->keyBy('code');
        if ($weights->isEmpty()) {
            return; // Needs weights
        }

        $dataMatrix = [];
        $mahasiswaIds = [];

        // 1. Collect Data (X)
        foreach ($mahasiswas as $mhs) {
            $c1 = 0; // IPK
            $c2 = 0; // Tren IPS
            $c3 = 0; // Nilai Bermasalah (D/E) -> cost
            $c4 = 0; // Konsultasi
            $c5 = 0; // Prestasi

            $totalBobot = 0;
            $totalSks = 0;
            $ipsHistory = [];
            
            $krsGrouped = $mhs->krs->groupBy('semester_id');
            foreach ($krsGrouped as $semesterId => $krsList) {
                $sksSem = 0;
                $bobotSem = 0;
                foreach ($krsList as $krs) {
                    if ($krs->nilai) {
                        $sks = $krs->mataKuliah->sks;
                        $sksSem += $sks;
                        $bobotSem += $krs->nilai->bobot * $sks;
                        $totalSks += $sks;
                        $totalBobot += $krs->nilai->bobot * $sks;
                        
                        if (in_array(strtoupper($krs->nilai->nilai_huruf), ['D', 'E'])) {
                            $c3++;
                        }
                    }
                }
                if ($sksSem > 0) {
                    $ipsHistory[] = $bobotSem / $sksSem;
                }
            }

            $c1 = $totalSks > 0 ? $totalBobot / $totalSks : 0;
            $ips_terakhir = end($ipsHistory) ?: 0;
            $ips_sebelumnya = count($ipsHistory) > 1 ? $ipsHistory[count($ipsHistory)-2] : $ips_terakhir;
            
            $c2 = $ips_terakhir - $ips_sebelumnya;
            $c4 = $mhs->pesanKeMahasiswa->count();
            $c5 = $mhs->prestasi->count();

            $mahasiswaIds[] = $mhs->id;
            $dataMatrix[] = [
                'C1' => $c1,
                'C2' => $c2,
                'C3' => $c3,
                'C4' => $c4,
                'C5' => $c5,
            ];
        }

        $this->applyTopsis($dataMatrix, $mahasiswaIds, $weights);
    }

    private function applyTopsis($dataMatrix, $mahasiswaIds, $weights)
    {
        $criteriaCols = ['C1', 'C2', 'C3', 'C4', 'C5'];
        
        // 2. Normalized Matrix (R)
        $pembagi = [];
        foreach ($criteriaCols as $c) {
            $sumSq = 0;
            foreach ($dataMatrix as $row) {
                $sumSq += pow($row[$c], 2);
            }
            $pembagi[$c] = sqrt($sumSq) == 0 ? 1 : sqrt($sumSq);
        }

        $R = [];
        foreach ($dataMatrix as $i => $row) {
            foreach ($criteriaCols as $c) {
                $R[$i][$c] = $row[$c] / $pembagi[$c];
            }
        }

        // 3. Weighted Normalized Matrix (Y)
        $Y = [];
        foreach ($R as $i => $row) {
            foreach ($criteriaCols as $c) {
                $weight = $weights->has($c) ? $weights[$c]->weight : 0;
                $Y[$i][$c] = $row[$c] * $weight;
            }
        }

        // 4. Positive and Negative Ideal Solutions (A+, A-)
        $A_plus = [];
        $A_minus = [];
        foreach ($criteriaCols as $c) {
            $colValues = array_column($Y, $c);
            if (empty($colValues)) {
                $A_plus[$c] = 0;
                $A_minus[$c] = 0;
                continue;
            }
            
            $type = $weights->has($c) ? $weights[$c]->type : 'benefit';
            if ($type == 'benefit') {
                $A_plus[$c] = max($colValues);
                $A_minus[$c] = min($colValues);
            } else { // cost
                $A_plus[$c] = min($colValues);
                $A_minus[$c] = max($colValues);
            }
        }

        // 5. Distance and Preference Value (V)
        $predictions = [];
        foreach ($Y as $i => $row) {
            $d_plus_sq = 0;
            $d_minus_sq = 0;
            
            foreach ($criteriaCols as $c) {
                $d_plus_sq += pow($row[$c] - $A_plus[$c], 2);
                $d_minus_sq += pow($row[$c] - $A_minus[$c], 2);
            }
            
            $d_plus = sqrt($d_plus_sq);
            $d_minus = sqrt($d_minus_sq);
            
            $v = ($d_plus + $d_minus) == 0 ? 0 : $d_minus / ($d_plus + $d_minus);
            
            // Category Determination
            if ($v >= 0.6) {
                $category = 'Hijau';
                $recommendation = 'Aman. Pertahankan performa akademik Anda.';
            } elseif ($v >= 0.3) {
                $category = 'Kuning';
                $recommendation = 'Perlu Monitoring. Tingkatkan fokus pada perkuliahan dan perbaiki IPS.';
            } else {
                $category = 'Merah';
                $recommendation = 'Perlu Pendampingan Khusus. Segera jadwalkan konsultasi intensif dengan dosen wali.';
            }

            $predictions[] = [
                'mahasiswa_id' => $mahasiswaIds[$i],
                'score' => $v,
                'category' => $category,
                'recommendation' => $recommendation,
                'details' => json_encode($dataMatrix[$i]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Save to DB
        AcademicPrediction::whereIn('mahasiswa_id', $mahasiswaIds)->delete();
        AcademicPrediction::insert($predictions);
    }
}
