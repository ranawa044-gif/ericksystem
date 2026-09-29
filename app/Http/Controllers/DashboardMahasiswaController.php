<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Services\PamasesaIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DashboardMahasiswaController extends Controller
{
    protected PamasesaIntegrationService $pamasesaService;

    public function __construct(PamasesaIntegrationService $pamasesaService)
    {
        $this->pamasesaService = $pamasesaService;
    }

    public function show(Request $request, string $nim)
    {
        $user = Auth::user();

        if ($user->role === 'mahasiswa' && $user->mahasiswa?->nim !== $nim) {
            abort(403);
        }

        $mahasiswa = Mahasiswa::query()
            ->with(['dosenWali', 'catatanDosenWali.dosen', 'prestasi'])
            ->where('nim', $nim)
            ->firstOrFail();

        $semesters = Semester::query()
            ->whereHas('krs', fn ($query) => $query->where('mahasiswa_id', $mahasiswa->id))
            ->orderBy('urutan')
            ->get();

        $selectedSemesterId = $request->query('semester_id') ?? ($semesters->last()?->id ?? null);
        $isAllSemesters = $selectedSemesterId === 'all';

        if ($isAllSemesters) {
            // Tampilkan semua nilai
            $nilaiSemester = $mahasiswa->krs()
                ->with(['semester', 'mataKuliah', 'nilai'])
                ->whereHas('nilai')
                ->get();
        } else {
            // Nilai per semester tertentu
            $selectedSemesterId = (int) $selectedSemesterId;
            $nilaiSemester = $mahasiswa->krs()
                ->with(['semester', 'mataKuliah', 'nilai'])
                ->where('semester_id', $selectedSemesterId)
                ->whereHas('nilai')
                ->get();
        }

        $semuaNilai = $mahasiswa->krs()
            ->with(['semester', 'mataKuliah', 'nilai'])
            ->whereHas('nilai')
            ->get();

        $ips = $this->hitungIndeksPrestasi($nilaiSemester);
        $ipk = $this->hitungIndeksPrestasi($semuaNilai);
        
        // Saat "Semua Semester", IPS yang ditampilkan adalah IPK kumulatif
        if ($isAllSemesters) {
            $ips = $ipk;
        }
        
        $totalSks = $semuaNilai->sum(fn ($krs) => $krs->mataKuliah->sks);
        $jumlahLulus = $semuaNilai->filter(fn ($krs) => $krs->nilai?->status_lulus)->count();
        $jumlahBelumLulus = $semuaNilai->filter(fn ($krs) => ! $krs->nilai?->status_lulus)->count();
        $totalPrestasi = $mahasiswa->prestasi->count();
        $statistikIpk = $this->statistikIpkPerSemester($semesters, $semuaNilai);

        $currentSemester = $semesters->last();
        $currentSemesterName = $currentSemester?->nama ?? 'Belum ada semester aktif';
        $semesterProgress = $semesters->count();

        $academicEnergy = (int) min(100, max(0, round(($ipk / 4) * 100)));
        $academicHealth = $this->academicHealth($ipk);

        $timelineItems = $this->buildTimeline($semesters, $semuaNilai);
        $latestPrestasi = $mahasiswa->prestasi->take(3);
        $missionChecklist = $this->missionChecklist($ipk, $jumlahBelumLulus, $totalPrestasi);
        $motivationalQuote = $this->motivationalQuote();
        $motivationalQuotes = $this->motivationalQuotes();

        $prediction = \App\Models\AcademicPrediction::where('mahasiswa_id', $mahasiswa->id)->first();
        $paSummary = $this->pamasesaService->getStudentPaSummary($mahasiswa->nim);

        return view('dashboard.mahasiswa', [
            'mahasiswa' => $mahasiswa,
            'semesters' => $semesters,
            'selectedSemesterId' => $selectedSemesterId,
            'isAllSemesters' => $isAllSemesters,
            'nilaiSemester' => $nilaiSemester,
            'ips' => $ips,
            'ipk' => $ipk,
            'totalSks' => $totalSks,
            'jumlahLulus' => $jumlahLulus,
            'jumlahBelumLulus' => $jumlahBelumLulus,
            'totalPrestasi' => $totalPrestasi,
            'statistikIpk' => $statistikIpk,
            'currentSemesterName' => $currentSemesterName,
            'semesterProgress' => $semesterProgress,
            'academicEnergy' => $academicEnergy,
            'academicHealth' => $academicHealth,
            'timelineItems' => $timelineItems,
            'latestPrestasi' => $latestPrestasi,
            'missionChecklist' => $missionChecklist,
            'motivationalQuote' => $motivationalQuote,
            'motivationalQuotes' => $motivationalQuotes,
            'prediction' => $prediction,
            'paSummary' => $paSummary,
        ]);
    }

    private function hitungIndeksPrestasi(Collection $krsCollection): float
    {
        $totalSks = $krsCollection->sum(fn ($krs) => $krs->mataKuliah->sks);

        if ($totalSks === 0) {
            return 0;
        }

        $totalBobot = $krsCollection->sum(function ($krs) {
            return $krs->mataKuliah->sks * ($krs->nilai->bobot ?? 0);
        });

        return round($totalBobot / $totalSks, 2);
    }

    private function statistikIpkPerSemester(Collection $semesters, Collection $semuaNilai): array
    {
        $labels = [];
        $values = [];

        foreach ($semesters as $semester) {
            $krsKumulatif = $semuaNilai->filter(function ($krs) use ($semester) {
                return $krs->semester && $krs->semester->urutan <= $semester->urutan;
            });

            if ($krsKumulatif->isEmpty()) {
                continue;
            }

            $labels[] = $semester->nama;
            $values[] = $this->hitungIndeksPrestasi($krsKumulatif);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function academicHealth(float $ipk): array
    {
        if ($ipk >= 3.5) {
            return ['label' => 'Excellent', 'description' => 'Kondisi akademik sangat kuat. Tetap pertahankan fokusmu!', 'class' => 'status-aman'];
        }

        if ($ipk >= 3.0) {
            return ['label' => 'Healthy', 'description' => 'Kamu berada di jalur yang baik. Lanjutkan kontribusi positifmu.', 'class' => 'status-aman'];
        }

        if ($ipk >= 2.5) {
            return ['label' => 'Stable', 'description' => 'Cukup baik, tapi ada ruang untuk perbaikan di beberapa mata kuliah.', 'class' => 'status-perhatian'];
        }

        if ($ipk >= 2.0) {
            return ['label' => 'Warning', 'description' => 'Perlu memastikan semua tugas dan review nilai tetap on track.', 'class' => 'status-perhatian'];
        }

        return ['label' => 'At Risk', 'description' => 'Waktu terbaik untuk bicara dengan dosen wali atau konsultan akademik.', 'class' => 'status-kritis'];
    }

    private function buildTimeline(Collection $semesters, Collection $semuaNilai): array
    {
        return $semesters->map(function ($semester) use ($semuaNilai) {
            $semesterKrs = $semuaNilai->where('semester_id', $semester->id);
            $sks = $semesterKrs->sum(fn ($krs) => $krs->mataKuliah->sks);
            $gpa = $this->hitungIndeksPrestasi($semesterKrs);
            $status = 'not-started';

            if ($semesterKrs->isNotEmpty()) {
                $hasPending = $semesterKrs->filter(fn ($krs) => ! $krs->nilai?->status_lulus)->isNotEmpty();
                $status = $hasPending ? 'at-risk' : 'completed';
            }

            return [
                'label' => $semester->nama,
                'sks' => $sks,
                'gpa' => $gpa,
                'status' => $status,
            ];
        })->toArray();
    }

    private function missionChecklist(float $ipk, int $jumlahBelumLulus, int $totalPrestasi): array
    {
        $missions = [
            ['label' => 'Periksa nilai semester ini', 'completed' => false],
            ['label' => 'Jadwalkan konsultasi dengan dosen wali', 'completed' => $ipk >= 3.0 && $jumlahBelumLulus === 0],
            ['label' => 'Update portofolio prestasi terbaru', 'completed' => $totalPrestasi > 0],
        ];

        if ($jumlahBelumLulus > 0) {
            array_unshift($missions, ['label' => 'Prioritaskan mata kuliah belum lulus', 'completed' => false]);
        }

        if ($ipk < 2.75) {
            $missions[] = ['label' => 'Tinjau strategi belajar untuk IPK', 'completed' => false];
        }

        return $missions;
    }

    private function motivationalQuote(): string
    {
        $quotes = $this->motivationalQuotes();

        return $quotes[array_rand($quotes)];
    }

    private function motivationalQuotes(): array
    {
        return [
            'Setiap langkah kecil hari ini adalah kemajuan besar untuk tujuan besok.',
            'Fokus yang konsisten akan mengubah hasil study-mu menjadi prestasi nyata.',
            'Belajar bukan soal menambah beban, tapi soal memperkuat kemampuanmu.',
            'Ketika kamu yakin, kamu akan menemukan jalan untuk tetap maju.',
            'Jaga ritme belajarmu, dan sistematikamu akan membawa prestasi.',
        ];
    }

    public function uploadFoto(Request $request, string $nim)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa' || $user->mahasiswa?->nim !== $nim) {
            abort(403);
        }

        $validated = $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ]);

        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        // Delete old foto if exists
        if ($mahasiswa->foto && file_exists(public_path('uploads/' . $mahasiswa->foto))) {
            unlink(public_path('uploads/' . $mahasiswa->foto));
        }

        // Store new foto
        $file = $request->file('foto');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        $mahasiswa->update(['foto' => $filename]);

        return back()->with('success', 'Foto berhasil diunggah.');
    }
}
