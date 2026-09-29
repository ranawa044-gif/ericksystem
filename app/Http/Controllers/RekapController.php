<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getRekapData($request);
        return view('pengelola.rekap-mahasiswa', $data);
    }

    public function print(Request $request)
    {
        $data = $this->getRekapData($request);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pengelola.rekap-mahasiswa-print', $data);
        $pdf->setPaper('a3', 'landscape');
        
        return $pdf->stream('Rekap_Mahasiswa.pdf');
    }

    private function getRekapData(Request $request)
    {
        $semesters = Semester::orderBy('urutan')->get();
        $semesterId = $request->get('semester_id') ?? ($semesters->last()->id ?? null);
        
        $dosenId = null;
        $dosens = collect();
        if (Auth::user()->role === 'admin') {
            $dosens = \App\Models\Dosen::orderBy('nama')->get();
            $dosenId = $request->get('dosen_id', 'all');
        }
        
        // Query mahasiswa
        $mahasiswas = Mahasiswa::with(['dosenWali', 'krs.mataKuliah', 'krs.nilai'])
            ->whereHas('krs', function ($q) use ($semesterId) {
                if ($semesterId !== 'all') {
                    $q->where('semester_id', $semesterId);
                }
            })
            ->when(Auth::user()->role === 'dosen', function ($query) {
                $query->where('dosen_wali_id', Auth::user()->dosen_id);
            })
            ->when(Auth::user()->role === 'admin' && $dosenId !== 'all', function ($query) use ($dosenId) {
                $query->where('dosen_wali_id', $dosenId);
            })
            ->orderBy('nim')
            ->get();

        // Hitung IPS (semester) dan IPK (kumulatif)
        $mahasiswas = $mahasiswas->map(function ($m) use ($semesterId) {
            if ($semesterId === 'all') {
                // Jika all, IPS per semester ditampilkan per semester (info untuk referensi)
                // IPK tetap kumulatif dari semua semester
                $krsSemua = $m->krs->filter(fn($k) => $k->nilai);
                $ipk = $this->hitungIndeksPrestasi($krsSemua);
                $m->ips = 0; // dummy untuk all
                $m->ipk = $ipk;
                $m->status_semester = '-'; // Status per semester tidak berlaku untuk 'all'
            } else {
                // Per semester tertentu
                $krsSemester = $m->krs->filter(fn($k) => $k->semester_id == $semesterId && $k->nilai);
                $krsSemua = $m->krs->filter(fn($k) => $k->nilai);

                $ips = $this->hitungIndeksPrestasi($krsSemester);
                $ipk = $this->hitungIndeksPrestasi($krsSemua);

                $m->ips = $ips;
                $m->ipk = $ipk;

                // Hitung status per semester
                $status = $this->hitungStatusSemester($krsSemester, $ips);
                $m->status_semester = $status;
            }

            return $m;
        });

        // Kelompokkan berdasarkan dosen wali
        $grouped = $mahasiswas->groupBy(fn ($m) => $m->dosenWali?->id ?? 0)
            ->map(function ($group) use ($semesterId) {
                // urutkan berdasarkan IPS desc dan beri peringkat (1 = tertinggi)
                if ($semesterId === 'all') {
                    // Untuk 'all', urutkan berdasarkan IPK
                    $sorted = $group->sortByDesc(fn($m) => $m->ipk)->values();
                } else {
                    $sorted = $group->sortByDesc(fn($m) => $m->ips)->values();
                }
                
                $ranked = $sorted->map(function ($m, $index) {
                    $m->rank = $index + 1;
                    return $m;
                });

                return $ranked;
            });

        return compact('semesters', 'semesterId', 'grouped', 'dosens', 'dosenId');
    }

    /**
     * Hitung indeks prestasi dari koleksi KRS.
     *
     * @param  Collection<int, \App\Models\Krs>  $krsCollection
     */
    private function hitungIndeksPrestasi(Collection $krsCollection): float
    {
        $totalSks = $krsCollection->sum(fn ($krs) => $krs->mataKuliah->sks ?? 0);

        if ($totalSks === 0) {
            return 0;
        }

        $totalBobot = $krsCollection->sum(function ($krs) {
            return ($krs->mataKuliah->sks ?? 0) * ($krs->nilai->bobot ?? 0);
        });

        return round($totalBobot / $totalSks, 2);
    }

    /**
     * Hitung status semester berdasarkan kondisi IPS, jumlah nilai D dan E.
     *
     * Kondisi:
     * 1. IPS >= 2 dan D <= 4 dan E = 0 → Tetap
     * 2. IPS >= 2 dan D > 4 dan E = 0 → Percobaan
     * 3. IPS >= 1.75 dan D <= 8 dan E = 0 → Percobaan
     * Selain itu → Tidak Lulus
     *
     * @param  Collection<int, \App\Models\Krs>  $krsCollection
     */
    private function hitungStatusSemester(Collection $krsCollection, float $ips): string
    {
        // Hitung jumlah nilai D dan E
        $nilaiD = $krsCollection->filter(fn ($k) => $k->nilai?->nilai_huruf === 'D')->count();
        $nilaiE = $krsCollection->filter(fn ($k) => $k->nilai?->nilai_huruf === 'E')->count();

        // Kondisi 1: IPS >= 2 dan D <= 4 dan E = 0
        if ($ips >= 2.0 && $nilaiD <= 4 && $nilaiE === 0) {
            return 'Tetap';
        }

        // Kondisi 2: IPS >= 2 dan D > 4 dan E = 0
        if ($ips >= 2.0 && $nilaiD > 4 && $nilaiE === 0) {
            return 'Percobaan';
        }

        // Kondisi 3: IPS >= 1,75 dan D <= 8 dan E = 0
        if ($ips >= 1.75 && $nilaiD <= 8 && $nilaiE === 0) {
            return 'Percobaan';
        }

        // Selain itu → Tidak Lulus
        return 'Tidak Lulus';
    }
}
