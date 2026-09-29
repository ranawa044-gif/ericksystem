# Fitur dan Modul Aplikasi ERICK

Berikut adalah dokumentasi teknis dan fungsional dari modul-modul yang tersedia di **ERICK** (Evaluasi Rekam Indeks, Capaian dan Konsultasi Akademik).

## 1. Modul Dashboard Mahasiswa
**Fungsi:** Menyediakan rekapitulasi data akademik untuk mahasiswa secara personal.
- **Statistik Cepat (Quick Stats):** Menghitung Total SKS, IPK (Indeks Prestasi Kumulatif), dan SKS Lulus secara real-time langsung melalui model Eloquent (bukan disimpan pasif di database).
- **Tabel Rekap Nilai:** Menampilkan daftar nilai per mata kuliah. Mahasiswa dapat menggunakan filter *Semester* untuk menyortir data.
- **Tabel IPS (Indeks Prestasi Semester):** Mengkalkulasi nilai agregat setiap semester yang telah dijalani.
- **Catatan Akademik:** Menampilkan pesan motivasi atau peringatan yang diberikan langsung oleh Dosen Wali.

## 2. Modul Konsultasi & Pesan (Dosen - Mahasiswa)
**Fungsi:** Sistem korespondensi tertutup antara mahasiswa dan dosen walinya.
- **Alur Mahasiswa:** Mahasiswa mengirim pesan dengan menentukan Kategori (Umum, Kritik, Saran, Pertanyaan). Mereka hanya bisa mengirim ke dosen wali mereka sendiri.
- **Alur Dosen:** Dosen melihat kotak masuk dari *semua* mahasiswa walinya di satu halaman terpadu.
- **Filter Pencarian:** Dosen dapat memfilter pesan masuk berdasarkan Nama/NIM Mahasiswa, Jenis Pesan, atau Tanggal Masuk.
- **Paginasi:** Pesan akan dibatasi 30 per halaman, memastikan performa optimal dan tampilan tidak memanjang.
- **Balas Pesan:** Dosen dapat memberikan balasan (atau mengedit balasannya) langsung di kartu pesan tersebut.

## 3. Modul Input Nilai (Admin / Dosen)
**Fungsi:** Pintu masuk data akademik.
- **Prioritas UX:** Tombol "Input via Excel" diposisikan di atas sebagai prioritas utama (karena input kolektif lebih sering dilakukan daripada input satu per satu).
- **Validasi Ganda:** Sistem mendeteksi kemungkinan entri nilai ganda atau bentrok saat mengimpor Excel.
- **Tabel Penyesuaian:** Menggunakan sistem paginasi agar daftar input yang panjang mudah dikelola.

## 4. Pengelolaan Pengguna (User Management)
**Fungsi:** Mengelola data *master* Dosen dan Mahasiswa.
- Tersedia pada menu `Data Mahasiswa` dan `Data Dosen`.
- Dilengkapi integrasi akun login. Jika seorang mahasiswa baru ditambahkan, sistem otomatis membuatkan baris autentikasi di tabel `users` dengan menyinkronkan NIM dan email (`nim@luminara.id`).
- Tersedia filter prodi dan nama.

## Batasan Sistem (Sistem Berjalan)
Sesuai arahan teknis awal:
1. **Tidak merubah Database:** Segala bentuk penambahan fitur (seperti Paginasi atau Filter) dilakukan melalui modifikasi di tingkat *Controller Logic Query Builder* tanpa merusak skema tabel (`migrations`) yang sudah ada.
2. **Tidak merusak Authentication:** Mekanisme multi-role (Admin, Dosen, Mahasiswa) yang telah ditenagai oleh Middleware bawaan Laravel dipertahankan. Halaman otentikasi (`login.blade.php`) murni direkayasa pada lapisan presentasi saja (*View*).
