<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\PrestasiMahasiswa;
use App\Models\Semester;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportCenterController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function dosenPerwalianPreview()
    {
        $user = auth()->user();
        if ($user->role !== 'dosen' && $user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        if ($user->role === 'dosen' && !$user->dosen_id) {
            abort(404, 'Dosen ID tidak ditemukan.');
        }

        $dosen = $user->dosen;
        $mahasiswas = Mahasiswa::where('dosen_wali_id', $user->dosen_id)
            ->with(['krs.nilai', 'prestasi', 'pesanKeMahasiswa'])
            ->get();

        $activeSemester = Semester::orderBy('id', 'desc')->first();
        
        $totalConsultations = 0;
        $mahasiswaCount = $mahasiswas->count();
        $belumPerwalian = 0;
        $sudahPerwalian = 0;
        $sp1 = 0;
        $sp2 = 0;
        $sp3 = 0;

        return view('reports.dosen_perwalian_preview', compact(
            'dosen', 'mahasiswaCount', 'activeSemester', 
            'sp1', 'sp2', 'sp3', 'sudahPerwalian', 'belumPerwalian'
        ));
    }

    public function dosenPerwalianPdf(Request $request)
    {
        // dd($request->all());
        $pdf = Pdf::loadView('reports.pdf.dosen_perwalian', ['data' => $request->all()]);
        return $pdf->stream('Laporan_Perwalian_Dosen.pdf');
    }

    public function studentPortfolioPdf()
    {
        $user = auth()->user();
        if ($user->role !== 'mahasiswa' || !$user->mahasiswa_id) {
            abort(403, 'Akses ditolak.');
        }

        $mahasiswa = Mahasiswa::with(['dosenWali', 'krs.mataKuliah', 'krs.nilai', 'prestasi', 'pesanKeMahasiswa', 'krs.semester'])
            ->findOrFail($user->mahasiswa_id);

        $semesters = Semester::orderBy('id', 'asc')->get();
        $grafikData = [];
        
        $totalBobotAll = 0;
        $totalSksAll = 0;

        foreach ($semesters as $semester) {
            $krsSemester = $mahasiswa->krs->where('semester_id', $semester->id);
            $sksSemester = $krsSemester->sum(fn($k) => $k->mataKuliah->sks);
            $bobotSemester = $krsSemester->sum(fn($k) => ($k->nilai->bobot ?? 0) * $k->mataKuliah->sks);
            $ips = $sksSemester > 0 ? $bobotSemester / $sksSemester : 0;
            
            if ($sksSemester > 0) {
                $grafikData[] = [
                    'semester' => $semester->nama,
                    'ips' => $ips
                ];
            }

            $totalSksAll += $sksSemester;
            $totalBobotAll += $bobotSemester;
        }

        $ipk = $totalSksAll > 0 ? $totalBobotAll / $totalSksAll : 0;
        $mahasiswa->ipk = $ipk;

        $pdf = Pdf::loadView('reports.pdf.student_portfolio', compact('mahasiswa', 'grafikData'));
        
        return $pdf->stream('Student_Academic_Portfolio_' . $mahasiswa->nim . '.pdf');
    }

    public function prestasiExport(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengekspor data prestasi.');
        }

        $query = PrestasiMahasiswa::with('mahasiswa')->orderBy('tanggal', 'desc');

        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        if ($request->tingkat) {
            $query->where('tingkat', $request->tingkat);
        }
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $prestasis = $query->get();

        if ($request->format === 'csv') {
            $filename = "Prestasi_Report_" . date('Ymd_His') . ".csv";
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            
            $columns = ['NIM', 'Nama Mahasiswa', 'Nama Prestasi', 'Tingkat', 'Kategori', 'Tanggal', 'Peringkat', 'Bukti'];
            
            $callback = function() use($prestasis, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                
                foreach ($prestasis as $p) {
                    fputcsv($file, [
                        $p->mahasiswa->nim,
                        $p->mahasiswa->nama,
                        $p->nama_prestasi,
                        $p->tingkat,
                        $p->kategori,
                        $p->tanggal,
                        $p->peringkat,
                        $p->bukti_url
                    ]);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }

        $pdf = Pdf::loadView('reports.pdf.prestasi', compact('prestasis'));
        return $pdf->stream('Prestasi_Report.pdf');
    }

    public function academicRiskPdf()
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->role !== 'dosen') {
            abort(403, 'Akses ditolak.');
        }

        $query = Mahasiswa::with(['krs.mataKuliah', 'krs.nilai', 'krs.semester']);
        if ($user->role === 'dosen') {
            $query->where('dosen_wali_id', $user->dosen_id);
        }

        $semuaMahasiswa = $query->get();
        $risikoMahasiswa = [];

        foreach ($semuaMahasiswa as $mhs) {
            $totalBobotAll = 0;
            $totalSksAll = 0;
            $ipsHistory = [];

            // Urutkan KRS berdasarkan semester
            $krsGrouped = $mhs->krs->groupBy('semester_id');
            
            foreach ($krsGrouped as $semesterId => $krsList) {
                $sksSem = 0;
                $bobotSem = 0;
                foreach ($krsList as $krs) {
                    if ($krs->nilai) {
                        $sks = $krs->mataKuliah->sks;
                        $sksSem += $sks;
                        $bobotSem += $krs->nilai->bobot * $sks;
                        
                        $totalSksAll += $sks;
                        $totalBobotAll += $krs->nilai->bobot * $sks;
                    }
                }
                $ips = $sksSem > 0 ? $bobotSem / $sksSem : 0;
                if ($sksSem > 0) {
                    $ipsHistory[] = $ips;
                }
            }

            $ipk = $totalSksAll > 0 ? $totalBobotAll / $totalSksAll : 0;
            $mhs->ipk = $ipk;
            $mhs->ips_terakhir = end($ipsHistory) ?: 0;
            $mhs->ips_sebelumnya = count($ipsHistory) > 1 ? $ipsHistory[count($ipsHistory)-2] : $mhs->ips_terakhir;
            
            $alasan = [];
            if ($ipk < 3.00) {
                $alasan[] = 'IPK di bawah 3.00 (' . number_format($ipk, 2) . ')';
            }
            if ($mhs->ips_terakhir < $mhs->ips_sebelumnya) {
                $alasan[] = 'IPS terakhir turun (' . number_format($mhs->ips_sebelumnya, 2) . ' -> ' . number_format($mhs->ips_terakhir, 2) . ')';
            }

            if (count($alasan) > 0) {
                $mhs->alasan_risiko = implode(', ', $alasan);
                $risikoMahasiswa[] = $mhs;
            }
        }

        $pdf = Pdf::loadView('reports.pdf.academic_risk', compact('risikoMahasiswa'));
        return $pdf->stream('Academic_Risk_Report.pdf');
    }
}
