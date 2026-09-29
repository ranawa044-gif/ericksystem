<div align="center">
  
  # ERICK
  **(Evaluasi Rekam Indeks, Capaian, dan Konsultasi Akademik)**

  *Sistem Manajemen Akademik Modern Bergaya LUMINARA (2026)*

</div>

---

## 📖 Tentang Aplikasi
**ERICK** adalah sebuah kerangka aplikasi berbasis Laravel yang dirancang khusus untuk memodernisasi ekosistem akademik. ERICK tidak hanya sekadar sistem informasi akademik biasa, melainkan sebuah platform komprehensif yang mengintegrasikan rekap nilai, penghitungan indeks prestasi, dan sistem konsultasi (pesan) antara mahasiswa dengan dosen walinya, yang semuanya dibalut dalam desain antarmuka (UI/UX) futuristik bergaya *Antigravity*.

Aplikasi ini menggunakan filosofi **LUMINARA Design System**, dengan fokus pada kebersihan (*clean*), profesionalisme, *glassmorphism* tingkat lanjut, dan interaksi animasi dinamis.

---

## ✨ Fitur Utama

- **Dashboard Mahasiswa Interaktif:** Menampilkan rekap nilai per semester, IPS (Indeks Prestasi Semester), IPK (Indeks Prestasi Kumulatif), dan kalkulasi cerdas berbasis Eloquent.
- **Konsultasi & Pesan Dua Arah:** Mahasiswa dapat mengirimkan pesan (umum, saran, kritik, pertanyaan) kepada dosen wali mereka secara langsung, lengkap dengan fitur filter dan paginasi untuk kemudahan membaca riwayat konsultasi.
- **Manajemen Nilai Modern:** Admin/Dosen dapat melakukan input nilai secara manual maupun massal melalui fitur *Import Excel*.
- **Desain Futuristik Antigravity:** Halaman login dengan *CSS particle orbs* melayang, *sidebar* navigasi dengan pil kaca (*glass pill*), dan layout grid responsif.
- **Keamanan & Autentikasi:** Fitur login dengan opsi *Show/Hide Password* presisi, proteksi rute berbasis peran (*Role-Based Access Control*), dan pembatasan akses data antar pengguna.

*(Untuk penjelasan fitur yang lebih mendalam, silakan baca `docs/FEATURES.md`)*

---

## 📊 Report Center & Kalkulasi Metrik

Pada pembaruan **E-RICK v1.2B (Academic Report Template Alignment)**, telah ditambahkan pusat pelaporan terpadu dengan standar format resmi. Beberapa metrik dikalkulasi secara otomatis oleh sistem tanpa perlu intervensi manual:

- **Kondisi Sudah Perwalian:** Mahasiswa dianggap *Sudah Perwalian* jika tercatat memiliki minimal satu riwayat pesan/konsultasi kepada dosen wali pada log pesan sistem.
- **Kondisi Belum Perwalian:** Mahasiswa dihitung *Belum Perwalian* jika tidak ada rekaman interaksi pesan antara mahasiswa dan dosen wali sama sekali.
- **Peringatan Akademik (SP):** Surat Peringatan dihitung secara langsung (*on-the-fly*) berdasarkan batas Indeks Prestasi Kumulatif (IPK):
  - **SP 1:** IPK di bawah 2.70
  - **SP 2:** IPK di bawah 2.50
  - **SP 3:** IPK di bawah 2.00
- **Mahasiswa Perlu Perhatian:** Daftar prioritas untuk dosen wali jika mahasiswa mengalami penurunan Indeks Prestasi Semester (IPS) atau IPK di bawah 3.00.

---

## 🧠 Smart Academic Advisor (SPK TOPSIS)

Pada pembaruan **E-RICK v1.3**, telah ditambahkan fitur pendukung keputusan (SPK) menggunakan metode TOPSIS untuk memprioritaskan pendampingan akademik mahasiswa. Penentuan kategori mahasiswa didasarkan pada nilai preferensi akhir (*V*) dari kalkulasi TOPSIS yang mempertimbangkan 5 kriteria utama (IPK, Tren IPS, Nilai D/E, Konsultasi, dan Prestasi):

- **Kondisi Aman (Hijau):** Mahasiswa memiliki nilai *V* $\ge$ 0.6. Performanya dianggap sangat baik dan stabil. Rekomendasi: *"Pertahankan performa akademik Anda."*
- **Kondisi Monitoring (Kuning):** Mahasiswa memiliki nilai *V* $\ge$ 0.3 dan $<$ 0.6. Mulai ada indikasi penurunan atau masalah kecil. Rekomendasi: *"Tingkatkan fokus pada perkuliahan dan perbaiki IPS."*
- **Kondisi Pendampingan (Merah):** Mahasiswa memiliki nilai *V* $<$ 0.3. Membutuhkan intervensi langsung dari dosen wali. Rekomendasi: *"Segera jadwalkan konsultasi intensif dengan dosen wali."*

---

## 🎨 LUMINARA Design System

Aplikasi ini sangat mengutamakan aspek UI/UX. Beberapa pedoman warna utama:
- **Primary:** Biru / Cyan (`#06b6d4`)
- **Secondary:** Hijau Tosca (`#14b8a6`)
- **Dark Accent:** Deep Navy (`#0f172a`)
- **Light Mode Background:** Slate (`#f8fafc`)

Seluruh pedoman mengenai struktur CSS, *glassmorphism*, dan animasi diatur dalam `public/css/style.css`.
*(Untuk panduan desain dan antarmuka, silakan baca `docs/UI_GUIDE.md`)*

---

## 🚀 Panduan Instalasi & Penggunaan

### 1. Persiapan Lingkungan (Environment)
Pastikan Anda telah menginstal:
- PHP ^8.1
- Composer
- MySQL / MariaDB
- Node.js & NPM (Opsional untuk *asset bundling* jika diperlukan di kemudian hari)

### 2. Kloning & Instalasi Dependencies
```bash
# Clone atau salin proyek ini ke direktori lokal Anda
git clone <repository_url> ericksistem
cd ericksistem

# Install dependensi PHP
composer install
```

### 3. Konfigurasi Database
Salin file `.env.example` menjadi `.env` dan atur koneksi database:
```bash
cp .env.example .env
```
Sesuaikan bagian ini di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erick_db
DB_USERNAME=root
DB_PASSWORD=
```
Lalu *generate application key*:
```bash
php artisan key:generate
```

### 4. Migrasi & Seeder (Data Awal)
Jalankan perintah berikut untuk membuat tabel dan mengisi data *dummy* (Akun admin, dosen, mahasiswa, mata kuliah, dll):
```bash
php artisan migrate --seed
```

### 5. Menjalankan Aplikasi
```bash
php artisan serve
```
Aplikasi kini dapat diakses melalui: `http://127.0.0.1:8000/`

---

## 🔐 Akun Akses Contoh

Gunakan akun berikut setelah Anda menjalankan seeder untuk masuk ke dalam sistem:

| Role | Email Login | Password |
|---|---|---|
| **Mahasiswa** | `nim@luminara.id` *(Ganti NIM dengan NIM asli dari DB)* | `password` |
| **Dosen** | `dosen@example.com` | `password` |
| **Admin** | `admin@example.com` | `password` |

---

## 📂 Struktur Direktori Spesifik

- `app/Models/`: Model Eloquent dengan relasi lengkap (Nilai, Mahasiswa, Dosen, Pesan).
- `app/Http/Controllers/`: Logika sistem. *Perhatikan bahwa sistem kalkulasi IPK/IPS tidak ditaruh di DB, melainkan dihitung on-the-fly*.
- `resources/views/`: 
  - `auth/`: Halaman Login Antigravity.
  - `pesan/`: UI Konsultasi (Grid cards).
  - `pengelola/`: Dashboard admin/dosen.
- `public/css/style.css`: File utama gaya (Design System LUMINARA).

---

<div align="center">
  <b>Dikembangkan dengan ❤ untuk Ekosistem Akademik Masa Depan.</b><br>
  <i>Luminara - 2026</i>
</div>
