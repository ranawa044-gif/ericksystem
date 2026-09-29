<?php

namespace Database\Seeders;

use App\Models\CatatanDosenWali;
use App\Models\Dosen;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dosenWali = Dosen::updateOrCreate(
            ['nidn' => '0012047801'],
            [
                'nama' => 'Dr. Budi Santoso, M.Kom.',
                'jabatan' => 'Dosen Wali',
            ]
        );

        $mahasiswa = Mahasiswa::updateOrCreate(
            ['nim' => '231001001'],
            [
                'nama' => 'Andi Pratama',
                'program_studi' => 'Sistem Informasi',
                'angkatan' => 2023,
                'dosen_wali_id' => $dosenWali->id,
            ]
        );

        $semester1 = Semester::updateOrCreate(
            ['tahun_akademik' => '2023/2024', 'periode' => 'ganjil', 'urutan' => 1],
            ['nama' => 'Semester 1']
        );

        $semester2 = Semester::updateOrCreate(
            ['tahun_akademik' => '2023/2024', 'periode' => 'genap', 'urutan' => 2],
            ['nama' => 'Semester 2']
        );

        $mataKuliahs = collect([
            ['kode' => 'SI101', 'nama' => 'Pengantar Sistem Informasi', 'sks' => 3, 'semester_default' => 1],
            ['kode' => 'SI102', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 3, 'semester_default' => 1],
            ['kode' => 'SI103', 'nama' => 'Matematika Diskrit', 'sks' => 2, 'semester_default' => 1],
            ['kode' => 'SI201', 'nama' => 'Basis Data', 'sks' => 3, 'semester_default' => 2],
            ['kode' => 'SI202', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester_default' => 2],
            ['kode' => 'SI203', 'nama' => 'Analisis Proses Bisnis', 'sks' => 2, 'semester_default' => 2],
        ])->map(fn ($data) => MataKuliah::updateOrCreate(['kode' => $data['kode']], $data));

        $nilaiData = [
            ['kode' => 'SI101', 'semester_id' => $semester1->id, 'nilai_angka' => 88, 'nilai_huruf' => 'A', 'bobot' => 4.00],
            ['kode' => 'SI102', 'semester_id' => $semester1->id, 'nilai_angka' => 82, 'nilai_huruf' => 'A', 'bobot' => 4.00],
            ['kode' => 'SI103', 'semester_id' => $semester1->id, 'nilai_angka' => 76, 'nilai_huruf' => 'AB', 'bobot' => 3.50],
            ['kode' => 'SI201', 'semester_id' => $semester2->id, 'nilai_angka' => 85, 'nilai_huruf' => 'A', 'bobot' => 4.00],
            ['kode' => 'SI202', 'semester_id' => $semester2->id, 'nilai_angka' => 79, 'nilai_huruf' => 'AB', 'bobot' => 3.50],
            ['kode' => 'SI203', 'semester_id' => $semester2->id, 'nilai_angka' => 72, 'nilai_huruf' => 'B', 'bobot' => 3.00],
        ];

        foreach ($nilaiData as $item) {
            $mataKuliah = $mataKuliahs->firstWhere('kode', $item['kode']);

            $krs = Krs::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa->id,
                    'mata_kuliah_id' => $mataKuliah->id,
                    'semester_id' => $item['semester_id'],
                ],
                ['status' => 'diambil']
            );

            Nilai::updateOrCreate(
                ['krs_id' => $krs->id],
                [
                    'nilai_angka' => $item['nilai_angka'],
                    'nilai_huruf' => $item['nilai_huruf'],
                    'bobot' => $item['bobot'],
                    'status_lulus' => $item['bobot'] >= 2.00,
                ]
            );
        }

        CatatanDosenWali::firstOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal' => '2026-02-12',
                'catatan' => 'Prestasi akademik baik. Pertahankan konsistensi belajar dan mulai susun rencana peminatan untuk semester berikutnya.',
            ],
            ['dosen_id' => $dosenWali->id]
        );

        CatatanDosenWali::firstOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal' => '2026-05-10',
                'catatan' => 'Disarankan aktif mengikuti bimbingan akademik sebelum pengisian KRS agar beban SKS tetap seimbang.',
            ],
            ['dosen_id' => $dosenWali->id]
        );

        User::updateOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'mahasiswa_id' => $mahasiswa->id,
                'dosen_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'dosen@example.com'],
            [
                'name' => 'Dr. Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'mahasiswa_id' => null,
                'dosen_id' => $dosenWali->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Akademik',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'mahasiswa_id' => null,
                'dosen_id' => null,
            ]
        );
    }
}
