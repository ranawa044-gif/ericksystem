<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PamasesaIntegrationService
{
    protected ?bool $connectionWorking = null;

    /**
     * Periksa apakah koneksi database PAMASESA tersedia.
     */
    public function isAvailable(): bool
    {
        if ($this->connectionWorking !== null) {
            return $this->connectionWorking;
        }

        try {
            DB::connection('pamasesa')->getPdo();
            $this->connectionWorking = true;
        } catch (Throwable $e) {
            Log::warning('Integrasi PAMASESA: Tidak dapat terhubung ke database pamasesa - ' . $e->getMessage());
            $this->connectionWorking = false;
        }

        return $this->connectionWorking;
    }

    /**
     * Dapatkan ringkasan status Proyek Akhir untuk satu mahasiswa berdasarkan NIM.
     */
    public function getStudentPaSummary(?string $nim): array
    {
        $defaultOffline = [
            'is_connected' => false,
            'has_student' => false,
            'has_pa' => false,
            'status' => 'OFFLINE',
            'status_label' => 'Layanan Tidak Terhubung',
            'badge_class' => 'bg-slate-100 text-slate-600',
            'badge_bg' => '#f1f5f9',
            'badge_text' => '#475569',
            'message' => 'Layanan database PAMASESA sedang tidak aktif atau tidak dapat dijangkau.',
            'app_url' => config('services.pamasesa.app_url', 'http://pamasesa2.test'),
            'online_url' => config('services.pamasesa.online_url', 'https://masesa.luminara.web.id'),
        ];

        if (empty($nim) || ! $this->isAvailable()) {
            return $defaultOffline;
        }

        try {
            // 1. Cari data student di PAMASESA berdasarkan NIM
            $student = DB::connection('pamasesa')
                ->table('students')
                ->where('nim', $nim)
                ->first();

            if (! $student) {
                return [
                    'is_connected' => true,
                    'has_student' => false,
                    'has_pa' => false,
                    'nim' => $nim,
                    'status' => 'NOT_REGISTERED',
                    'status_label' => 'Belum Terdaftar di PAMASESA',
                    'badge_class' => 'bg-gray-100 text-gray-700',
                    'badge_bg' => '#f3f4f6',
                    'badge_text' => '#374151',
                    'message' => 'NIM mahasiswa belum terdaftar di sistem PAMASESA.',
                    'app_url' => config('services.pamasesa.app_url', 'http://pamasesa2.test'),
                    'online_url' => config('services.pamasesa.online_url', 'https://masesa.luminara.web.id'),
                ];
            }

            // 2. Cari data Final Project
            $finalProject = DB::connection('pamasesa')
                ->table('final_projects')
                ->where('student_id', $student->id)
                ->first();

            if (! $finalProject) {
                return [
                    'is_connected' => true,
                    'has_student' => true,
                    'has_pa' => false,
                    'student_id' => $student->id,
                    'student_name' => $student->nama,
                    'nim' => $student->nim,
                    'status' => 'NOT_SUBMITTED',
                    'status_label' => 'Belum Mengajukan Judul',
                    'badge_class' => 'bg-amber-50 text-amber-700 border border-amber-200',
                    'badge_bg' => '#fef3c7',
                    'badge_text' => '#b45309',
                    'message' => 'Mahasiswa belum mengajukan proposal / judul Proyek Akhir di PAMASESA.',
                    'app_url' => config('services.pamasesa.app_url', 'http://pamasesa2.test'),
                    'online_url' => config('services.pamasesa.online_url', 'https://masesa.luminara.web.id'),
                ];
            }

            // 3. Ambil data Pembimbing
            $supervisors = DB::connection('pamasesa')
                ->table('supervisors')
                ->leftJoin('lecturers', 'supervisors.lecturer_id', '=', 'lecturers.id')
                ->where('supervisors.final_project_id', $finalProject->id)
                ->select(
                    'supervisors.type',
                    'supervisors.guidance_score',
                    'supervisors.guidance_notes',
                    'lecturers.nama as lecturer_nama',
                    'lecturers.nidn as lecturer_nidn'
                )
                ->get();

            $p1 = $supervisors->firstWhere('type', 'PEMBIMBING_1');
            $p2 = $supervisors->firstWhere('type', 'PEMBIMBING_2');

            // 4. Ambil statistik logbook bimbingan
            $progressLogs = DB::connection('pamasesa')
                ->table('progress_logs')
                ->where('final_project_id', $finalProject->id)
                ->get();

            $totalLogs = $progressLogs->count();
            $verifiedLogs = $progressLogs->where('status', 'VERIFIED')->count();
            $verifiedP1 = $progressLogs->where('target_supervisor', 'PEMBIMBING_1')->where('status', 'VERIFIED')->count();
            $verifiedP2 = $progressLogs->where('target_supervisor', 'PEMBIMBING_2')->where('status', 'VERIFIED')->count();
            $latestLog = $progressLogs->sortByDesc('created_at')->first();

            // 5. Seminar Proposal info
            $seminar = DB::connection('pamasesa')
                ->table('seminar_proposals')
                ->where('final_project_id', $finalProject->id)
                ->first();

            // 6. Sidang Akhir info
            $defense = DB::connection('pamasesa')
                ->table('final_defenses')
                ->where('final_project_id', $finalProject->id)
                ->first();

            $statusMeta = $this->getStatusMeta($finalProject->status);

            return [
                'is_connected' => true,
                'has_student' => true,
                'has_pa' => true,
                'student_id' => $student->id,
                'student_name' => $student->nama,
                'nim' => $student->nim,
                'final_project_id' => $finalProject->id,
                'title' => $finalProject->title,
                'system_type' => $finalProject->system_type ?? 'Aplikasi Sistem Informasi',
                'raw_status' => $finalProject->status,
                'status_label' => $statusMeta['label'],
                'badge_class' => $statusMeta['badge_class'],
                'badge_bg' => $statusMeta['badge_bg'],
                'badge_text' => $statusMeta['badge_text'],
                'submitted_at' => $finalProject->submitted_at,
                'approved_at' => $finalProject->approved_at,
                'proposed_supervisor' => $finalProject->proposed_supervisor_name,
                'pembimbing_1' => $p1 ? $p1->lecturer_nama : ($finalProject->proposed_supervisor_name ?: 'Belum ditentukan'),
                'pembimbing_2' => $p2 ? $p2->lecturer_nama : 'Belum ditentukan',
                'supervisor_one_approval' => $finalProject->supervisor_one_approval ?? 'PENDING',
                'supervisor_two_approval' => $finalProject->supervisor_two_approval ?? 'PENDING',
                'progress' => [
                    'total_logs' => $totalLogs,
                    'verified_logs' => $verifiedLogs,
                    'verified_p1' => $verifiedP1,
                    'verified_p2' => $verifiedP2,
                    'meets_seminar' => ($verifiedP1 >= 2 && $verifiedP2 >= 1),
                    'meets_defense' => ($verifiedP1 >= 10 && $verifiedP2 >= 5),
                    'latest_date' => $latestLog?->created_at,
                ],
                'seminar' => [
                    'has_submitted' => (bool) $seminar,
                    'status' => $seminar?->status ?? 'BELUM_DAFTAR',
                ],
                'defense' => [
                    'has_submitted' => (bool) $defense,
                    'status' => $defense?->status ?? 'BELUM_DAFTAR',
                ],
                'app_url' => config('services.pamasesa.app_url', 'http://pamasesa2.test'),
                'online_url' => config('services.pamasesa.online_url', 'https://masesa.luminara.web.id'),
            ];
        } catch (Throwable $e) {
            Log::error('Integrasi PAMASESA getStudentPaSummary Error: ' . $e->getMessage());
            return $defaultOffline;
        }
    }

    /**
     * Dapatkan ringkasan status PA untuk banyak mahasiswa (Batch) berdasarkan array NIM.
     * Sangat efisien untuk halaman Dosen Wali (menghindari N+1 query).
     */
    public function getBatchPaSummary(array $nims): Collection
    {
        $result = collect();

        if (empty($nims) || ! $this->isAvailable()) {
            return $result;
        }

        try {
            $students = DB::connection('pamasesa')
                ->table('students')
                ->whereIn('nim', $nims)
                ->get()
                ->keyBy('nim');

            if ($students->isEmpty()) {
                return $result;
            }

            $studentIds = $students->pluck('id')->toArray();

            $finalProjects = DB::connection('pamasesa')
                ->table('final_projects')
                ->whereIn('student_id', $studentIds)
                ->get()
                ->keyBy('student_id');

            $fpIds = $finalProjects->pluck('id')->toArray();

            // Load supervisors
            $supervisors = collect();
            if (! empty($fpIds)) {
                $supervisors = DB::connection('pamasesa')
                    ->table('supervisors')
                    ->leftJoin('lecturers', 'supervisors.lecturer_id', '=', 'lecturers.id')
                    ->whereIn('supervisors.final_project_id', $fpIds)
                    ->select('supervisors.final_project_id', 'supervisors.type', 'lecturers.nama as lecturer_nama')
                    ->get()
                    ->groupBy('final_project_id');
            }

            // Load progress log count
            $progressCounts = collect();
            if (! empty($fpIds)) {
                $progressCounts = DB::connection('pamasesa')
                    ->table('progress_logs')
                    ->whereIn('final_project_id', $fpIds)
                    ->selectRaw('final_project_id, count(*) as total, sum(case when status="VERIFIED" then 1 else 0 end) as verified')
                    ->groupBy('final_project_id')
                    ->get()
                    ->keyBy('final_project_id');
            }

            foreach ($nims as $nim) {
                $student = $students->get($nim);
                if (! $student) {
                    $result->put($nim, [
                        'has_pa' => false,
                        'status' => 'NOT_REGISTERED',
                        'status_label' => 'Belum Terdaftar',
                        'badge_class' => 'bg-gray-100 text-gray-600',
                    ]);
                    continue;
                }

                $fp = $finalProjects->get($student->id);
                if (! $fp) {
                    $result->put($nim, [
                        'has_pa' => false,
                        'status' => 'NOT_SUBMITTED',
                        'status_label' => 'Belum Ajukan',
                        'badge_class' => 'bg-gray-100 text-gray-500',
                    ]);
                    continue;
                }

                $fpSupervisors = $supervisors->get($fp->id, collect());
                $p1 = $fpSupervisors->firstWhere('type', 'PEMBIMBING_1')?->lecturer_nama ?? $fp->proposed_supervisor_name;
                $p2 = $fpSupervisors->firstWhere('type', 'PEMBIMBING_2')?->lecturer_nama;
                $pCount = $progressCounts->get($fp->id);

                $statusMeta = $this->getStatusMeta($fp->status);

                $result->put($nim, [
                    'has_pa' => true,
                    'title' => $fp->title,
                    'status' => $fp->status,
                    'status_label' => $statusMeta['label'],
                    'badge_class' => $statusMeta['badge_class'],
                    'pembimbing_1' => $p1,
                    'pembimbing_2' => $p2,
                    'total_logs' => $pCount->total ?? 0,
                    'verified_logs' => $pCount->verified ?? 0,
                ]);
            }

            return $result;
        } catch (Throwable $e) {
            Log::error('Integrasi PAMASESA getBatchPaSummary Error: ' . $e->getMessage());
            return $result;
        }
    }

    /**
     * Format visual status PA ke label dan warna Tailwind.
     */
    protected function getStatusMeta(?string $status): array
    {
        return match (strtoupper($status ?? '')) {
            'SUBMITTED' => [
                'label' => 'Menunggu Review',
                'badge_class' => 'bg-amber-100 text-amber-800 border border-amber-200',
                'badge_bg' => '#fef3c7',
                'badge_text' => '#b45309',
            ],
            'APPROVED' => [
                'label' => 'Judul Disetujui',
                'badge_class' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                'badge_bg' => '#d1fae5',
                'badge_text' => '#065f46',
            ],
            'REVISION' => [
                'label' => 'Perlu Revisi Judul',
                'badge_class' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                'badge_bg' => '#fef9c3',
                'badge_text' => '#854d0e',
            ],
            'REJECTED' => [
                'label' => 'Judul Ditolak',
                'badge_class' => 'bg-rose-100 text-rose-800 border border-rose-200',
                'badge_bg' => '#ffe4e6',
                'badge_text' => '#9f1239',
            ],
            'READY_FOR_SEMINAR' => [
                'label' => 'Siap Sempro',
                'badge_class' => 'bg-sky-100 text-sky-800 border border-sky-200',
                'badge_bg' => '#e0f2fe',
                'badge_text' => '#0369a1',
            ],
            'READY_FOR_DEFENSE' => [
                'label' => 'Siap Sidang Akhir',
                'badge_class' => 'bg-purple-100 text-purple-800 border border-purple-200',
                'badge_bg' => '#f3e8ff',
                'badge_text' => '#6b21a8',
            ],
            'COMPLETED', 'PASSED' => [
                'label' => 'Lulus Sidang PA',
                'badge_class' => 'bg-teal-100 text-teal-800 border border-teal-200',
                'badge_bg' => '#ccfbf1',
                'badge_text' => '#115e59',
            ],
            default => [
                'label' => $status ? ucwords(strtolower(str_replace('_', ' ', $status))) : 'Belum Ditentukan',
                'badge_class' => 'bg-slate-100 text-slate-700 border border-slate-200',
                'badge_bg' => '#f1f5f9',
                'badge_text' => '#334155',
            ],
        };
    }
}
