# Walkthrough: E-RICK v1.3 Smart Academic Advisor

Sistem Pendukung Keputusan (SPK) untuk memprioritaskan pendampingan akademik mahasiswa menggunakan metode **TOPSIS** telah berhasil diimplementasikan!

## Apa yang Telah Diselesaikan?

1. **Model & Database**
   - Membuat model `SpkCriteriaWeight` untuk menyimpan bobot dinamis (C1-C5).
   - Membuat model `AcademicPrediction` untuk menyimpan riwayat hasil kalkulasi (*Aman, Monitoring, Pendampingan*).
   - Telah menyisipkan data default bobot ke dalam database (IPK 35%, Tren IPS 25%, Nilai Bermasalah 20%, Konsultasi 10%, Prestasi 10%).

2. **Core Logic: `AcademicDecisionService`**
   - Menulis *service* komprehensif yang melakukan normalisasi data, pembobotan, serta pencarian Jarak Solusi Ideal Positif & Negatif (*A+* / *A-*).
   - Perhitungan berjalan sesuai parameter dinamis dari KRS, Pesan, dan Prestasi, tanpa mengganggu model data lama.

3. **Admin Dashboard (SPK Analytics)**
   - Admin sekarang memiliki akses ke distribusi akademik seluruh mahasiswa (Global).
   - Fitur pengaturan bobot kriteria secara langsung (*real-time*). Setelah bobot diubah, sistem akan mengalkulasi ulang peringkat untuk semua mahasiswa secara otomatis.

4. **Dosen Wali Dashboard (Smart Advisor)**
   - Dosen wali memiliki akses spesifik untuk melihat daftar peringkat prioritas mahasiswanya sendiri.
   - Peringkat diurutkan dari skor *V* terendah (Merah/Prioritas) ke tertinggi (Hijau/Aman).
   - Dilengkapi kartu statistik (Aman, Monitoring, Pendampingan) dan fitur *Jalankan Analisis Terbaru* untuk memicu kalkulasi ulang secara manual jika ada pembaruan nilai/KRS.

5. **Mahasiswa Dashboard (Academic Insight)**
   - Hasil kalkulasi disematkan dengan bahasa yang halus ("Academic Insight") pada beranda mahasiswa.
   - Kata "risiko" telah dihindari. Rekomendasinya berfokus pada aksi, misalnya: *"Perlu Pendampingan Khusus. Segera jadwalkan konsultasi intensif dengan dosen wali."*

## Bagaimana Cara Mengujinya?
1. **Sebagai Admin**: Cek sidebar "SPK Analytics". Sesuaikan persentase bobot dan simpan.
2. **Sebagai Dosen Wali**: Cek sidebar "Smart Advisor". Tekan "Jalankan Analisis Terbaru", dan saksikan mahasiswa Anda diurutkan berdasarkan skala prioritas.
3. **Sebagai Mahasiswa**: Kunjungi halaman utama *Dashboard*. Anda akan melihat kotak khusus "Academic Insight" yang memberi tahu kondisi Anda dan rekomendasi terbaik.
