@extends('layouts.app')
@section('title', 'Dokumentasi Fitur ERICK')

@section('content')
<style>
    .feature-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: var(--page);
        border-radius: 0.75rem;
        border-left: 4px solid var(--primary);
    }
    
    .feature-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .feature-icon {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--primary);
        color: white;
        border-radius: 0.5rem;
        font-weight: bold;
    }
    
    .feature-description {
        color: var(--ink);
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    
    .feature-details {
        display: grid;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .detail-item {
        padding: 0.75rem;
        background: #f0f9ff;
        border-radius: 0.5rem;
        border-left: 2px solid var(--primary);
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--accent);
        font-size: 0.9rem;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }
    
    .detail-text {
        color: var(--ink);
        font-size: 0.95rem;
    }
    
    .category-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: var(--primary);
        color: white;
        border-radius: 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 0.5rem;
    }
    
    .spec-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0.75rem;
        font-size: 0.9rem;
    }
    
    .spec-table th {
        background: var(--accent);
        color: white;
        padding: 0.5rem;
        text-align: left;
        font-weight: 600;
    }
    
    .spec-table td {
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
        background: #fafafa;
    }
    
    .spec-table tr:hover td {
        background: #f3f4f6;
    }
    
    .topsis-flow {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .flow-item {
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 0.5rem;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .color-palette {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .color-item {
        padding: 1rem;
        border-radius: 0.5rem;
        text-align: center;
        font-weight: 500;
        color: var(--ink);
        border: 1px solid #e5e7eb;
    }
    
    .doc-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--primary);
    }
    
    .doc-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 0.5rem;
    }
    
    .doc-header p {
        color: var(--ink);
        font-size: 1.05rem;
    }
    
    .toc {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
    }
    
    .toc h3 {
        color: var(--accent);
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .toc ul {
        list-style: none;
        padding: 0;
    }
    
    .toc li {
        margin: 0.5rem 0;
    }
    
    .toc a {
        color: var(--primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .toc a:hover {
        text-decoration: underline;
    }
</style>

<div class="doc-header">
    <h1>📚 Dokumentasi Fitur ERICK</h1>
    <p>Panduan lengkap semua modul dan fitur dalam sistem manajemen akademik ERICK</p>
</div>

<!-- Table of Contents -->
<div class="toc">
    <h3>📋 Daftar Isi</h3>
    <ul>
        <li><a href="#dashboard">🎯 Dashboard Mahasiswa</a></li>
        <li><a href="#konsultasi">💬 Sistem Konsultasi & Pesan</a></li>
        <li><a href="#input-nilai">✏️ Manajemen Nilai</a></li>
        <li><a href="#import">📥 Import Excel Massal</a></li>
        <li><a href="#smart-advisor">🧠 Smart Academic Advisor (SPK TOPSIS)</a></li>
        <li><a href="#reports">📊 Report Center</a></li>
        <li><a href="#data-master">🗂️ Data Master</a></li>
        <li><a href="#design">🎨 Design System</a></li>
        <li><a href="#security">🔒 Keamanan & Akses</a></li>
    </ul>
</div>

<!-- 1. Dashboard Mahasiswa -->
<div class="feature-section" id="dashboard">
    <div class="feature-title">
        <span class="feature-icon">🎯</span>
        Dashboard Mahasiswa
    </div>
    <p class="feature-description">
        Menyediakan rekapitulasi data akademik untuk mahasiswa secara personal dengan statistik real-time.
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">🔢 Quick Stats</div>
            <div class="detail-text">Total SKS, IPK (dihitung real-time), SKS Lulus - tidak disimpan di database, kalkulasi on-the-fly</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📋 Tabel Rekap Nilai</div>
            <div class="detail-text">Daftar nilai per mata kuliah dengan filter semester. Tampilkan Kode MK, Nama MK, SKS, Nilai Angka, Huruf, Bobot, Status Lulus</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📊 Tabel IPS per Semester</div>
            <div class="detail-text">Indeks Prestasi Semester dengan visualisasi perbandingan antar semester</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">💌 Catatan Akademik</div>
            <div class="detail-text">Pesan motivasi/peringatan dari Dosen Wali yang ditampilkan dengan urutan terbaru</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">✨ Academic Insight</div>
            <div class="detail-text">Rekomendasi prediksi akademik dari SPK TOPSIS dengan kategori Hijau/Kuning/Merah</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📸 Upload Foto</div>
            <div class="detail-text">Mahasiswa dapat mengunggah foto profil ke /storage/uploads/</div>
        </div>
    </div>
    <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
        <strong>Akses:</strong> <span class="category-badge">Mahasiswa</span> <span class="category-badge">Dosen</span> <span class="category-badge">Admin</span>
    </p>
</div>

<!-- 2. Sistem Konsultasi -->
<div class="feature-section" id="konsultasi">
    <div class="feature-title">
        <span class="feature-icon">💬</span>
        Sistem Konsultasi & Pesan (Dosen-Mahasiswa)
    </div>
    <p class="feature-description">
        Sistem korespondensi tertutup dengan fitur filter, paginasi, dan balasan real-time antara mahasiswa dan dosen wali.
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">👤 Alur Mahasiswa (/mahasiswa/{nim}/pesan)</div>
            <div class="detail-text">
                <strong>Form Kirim:</strong> Pilih kategori pesan (Umum, Kritik, Saran, Pertanyaan)<br>
                Mahasiswa hanya bisa kirim ke Dosen Wali mereka sendiri<br>
                <strong>Riwayat:</strong> Ditampilkan dengan kartu-kartu yang tersimpan
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">👨‍🏫 Alur Dosen Wali (/dosen/pesan)</div>
            <div class="detail-text">
                <strong>Melihat Semua Pesan:</strong> Dari semua mahasiswa bimbingan dalam satu halaman<br>
                <strong>Filter:</strong> Nama/NIM Mahasiswa, Jenis Pesan, Tanggal Masuk<br>
                <strong>Paginasi:</strong> 30 pesan per halaman dengan custom inline styling
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">💬 Fitur Balas Pesan</div>
            <div class="detail-text">Dosen dapat memberikan atau mengedit balasan. Sistem menyimpan balasan_dosen dan dibalas_at timestamp</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">🎨 Grid Layout</div>
            <div class="detail-text">CSS Grid responsive untuk menghindari scrolling fatigue. Kartu pesan dengan overflow-y: auto untuk text panjang</div>
        </div>
    </div>
    <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
        <strong>Akses:</strong> <span class="category-badge">Mahasiswa</span> <span class="category-badge">Dosen</span>
    </p>
</div>

<!-- 3. Input Nilai -->
<div class="feature-section" id="input-nilai">
    <div class="feature-title">
        <span class="feature-icon">✏️</span>
        Manajemen Nilai (Input Nilai)
    </div>
    <p class="feature-description">
        Sistem input nilai manual dan massal dengan validasi otomatis serta konversi nilai realtime.
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">📝 Input Manual</div>
            <div class="detail-text">Form: NIM, Semester, Mata Kuliah, Nilai Angka (0-100). Otomatis konversi ke Nilai Huruf, Bobot, Status Lulus</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">✅ Validasi Ganda</div>
            <div class="detail-text">Deteksi entri nilai ganda/bentrok saat import. Validasi NIM, Mata Kuliah, Semester sudah ada</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📊 Tabel Penyesuaian</div>
            <div class="detail-text">Paginasi (10/25/50 per halaman). Search by NIM/Nama Mahasiswa atau Kode/Nama MK. Edit inline, Filter by Dosen Wali</div>
        </div>
    </div>
    <p style="margin-top: 1rem; margin-bottom: 1rem;">
        <strong>Skala Nilai & Konversi Bobot:</strong>
    </p>
    <table class="spec-table">
        <thead>
            <tr>
                <th>Huruf</th>
                <th>Rentang Angka</th>
                <th>Bobot</th>
                <th>Status Lulus</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>A</strong></td>
                <td>80 - 100</td>
                <td>4.00</td>
                <td>✓ Lulus</td>
            </tr>
            <tr>
                <td><strong>AB</strong></td>
                <td>73 - 79.99</td>
                <td>3.50</td>
                <td>✓ Lulus</td>
            </tr>
            <tr>
                <td><strong>B</strong></td>
                <td>66 - 72.99</td>
                <td>3.00</td>
                <td>✓ Lulus</td>
            </tr>
            <tr>
                <td><strong>BC</strong></td>
                <td>59 - 65.99</td>
                <td>2.50</td>
                <td>✓ Lulus</td>
            </tr>
            <tr>
                <td><strong>C</strong></td>
                <td>52 - 58.99</td>
                <td>2.00</td>
                <td>✓ Lulus</td>
            </tr>
            <tr>
                <td><strong>D</strong></td>
                <td>38 - 51.99</td>
                <td>1.00</td>
                <td>✗ Tidak Lulus</td>
            </tr>
            <tr>
                <td><strong>E</strong></td>
                <td>0 - 37.99</td>
                <td>0.00</td>
                <td>✗ Gagal</td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
        <strong>Akses:</strong> <span class="category-badge">Admin</span> <span class="category-badge">Dosen</span> (mahasiswa wali)
    </p>
</div>

<!-- 4. Import Excel -->
<div class="feature-section" id="import">
    <div class="feature-title">
        <span class="feature-icon">📥</span>
        Import Excel Massal
    </div>
    <p class="feature-description">
        Sistem import data mahasiswa dan nilai dalam jumlah besar menggunakan file Excel dengan validasi otomatis.
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">👥 Import Mahasiswa</div>
            <div class="detail-text">
                <strong>Kolom Template:</strong> NIM, Nama, Program Studi, Angkatan, Dosen Wali NIDN, Email, Password<br>
                <strong>Proses:</strong> CreateOrUpdate Mahasiswa + Otomatis buat user login + Link ke Dosen Wali + Password di-hash<br>
                <strong>Output:</strong> Laporan Berhasil/Dilewati
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📊 Import Nilai</div>
            <div class="detail-text">
                <strong>Kolom Template:</strong> NIM, Semester Urutan, Tahun Akademik, Periode, Kode MK, Nilai Angka<br>
                <strong>Proses:</strong> Cari/buat KRS + Otomatis konversi nilai + Validasi duplikat<br>
                <strong>Output:</strong> Laporan detail import
            </div>
        </div>
    </div>
    <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
        <strong>Akses:</strong> <span class="category-badge">Admin</span>
    </p>
</div>

<!-- 5. Smart Academic Advisor -->
<div class="feature-section" id="smart-advisor">
    <div class="feature-title">
        <span class="feature-icon">🧠</span>
        Smart Academic Advisor (SPK TOPSIS)
    </div>
    <p class="feature-description">
        Sistem Pendukung Keputusan menggunakan metode TOPSIS untuk memprioritaskan pendampingan akademik mahasiswa berdasarkan 5 kriteria utama.
    </p>
    
    <p style="margin: 1rem 0; font-weight: 600; color: var(--accent);">
        📌 Kriteria Analisis (5 Kriteria):
    </p>
    <table class="spec-table" style="margin-bottom: 1rem;">
        <thead>
            <tr>
                <th>Kriteria</th>
                <th>Kode</th>
                <th>Tipe</th>
                <th>Bobot Default</th>
                <th>Penjelasan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>IPK</strong></td>
                <td>C1</td>
                <td>Benefit ↑</td>
                <td>35%</td>
                <td>Indeks Prestasi Kumulatif (semakin tinggi semakin baik)</td>
            </tr>
            <tr>
                <td><strong>Tren IPS</strong></td>
                <td>C2</td>
                <td>Benefit ↑</td>
                <td>25%</td>
                <td>Perubahan IPS semester terakhir vs sebelumnya</td>
            </tr>
            <tr>
                <td><strong>Nilai Bermasalah</strong></td>
                <td>C3</td>
                <td>Cost ↓</td>
                <td>20%</td>
                <td>Jumlah nilai D/E (semakin sedikit semakin baik)</td>
            </tr>
            <tr>
                <td><strong>Konsultasi</strong></td>
                <td>C4</td>
                <td>Benefit ↑</td>
                <td>10%</td>
                <td>Jumlah pesan ke dosen wali (engagement)</td>
            </tr>
            <tr>
                <td><strong>Prestasi</strong></td>
                <td>C5</td>
                <td>Benefit ↑</td>
                <td>10%</td>
                <td>Jumlah prestasi akademik + non-akademik</td>
            </tr>
        </tbody>
    </table>
    
    <p style="margin: 1rem 0; font-weight: 600; color: var(--accent);">
        🔄 Langkah Kalkulasi TOPSIS:
    </p>
    <div class="topsis-flow">
        <div class="flow-item">1. Kumpulkan Data</div>
        <div class="flow-item">2. Normalisasi (R)</div>
        <div class="flow-item">3. Weighted (Y)</div>
        <div class="flow-item">4. Ideal A+/A-</div>
        <div class="flow-item">5. Hitung D+/D-</div>
        <div class="flow-item">6. Nilai V = D-/(D++D-)</div>
    </div>
    
    <p style="margin: 1rem 0; font-weight: 600; color: var(--accent);">
        🎨 Kategori Hasil Prediksi:
    </p>
    <div class="feature-details">
        <div class="detail-item" style="background: #f0fdf4; border-left-color: #10b981;">
            <div class="detail-label">🟢 Hijau (V ≥ 0.6)</div>
            <div class="detail-text"><strong>Status:</strong> Aman<br><strong>Rekomendasi:</strong> "Pertahankan performa akademik Anda."</div>
        </div>
        <div class="detail-item" style="background: #fefce8; border-left-color: #f59e0b;">
            <div class="detail-label">🟡 Kuning (0.3 ≤ V < 0.6)</div>
            <div class="detail-text"><strong>Status:</strong> Monitoring<br><strong>Rekomendasi:</strong> "Tingkatkan fokus pada perkuliahan dan perbaiki IPS."</div>
        </div>
        <div class="detail-item" style="background: #fef2f2; border-left-color: #ef4444;">
            <div class="detail-label">🔴 Merah (V < 0.3)</div>
            <div class="detail-text"><strong>Status:</strong> Pendampingan<br><strong>Rekomendasi:</strong> "Segera jadwalkan konsultasi intensif dengan dosen wali."</div>
        </div>
    </div>
    
    <p style="margin: 1rem 0; font-weight: 600; color: var(--accent);">
        👥 Akses & Dashboard:
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">Admin Dashboard (/advisor/admin)</div>
            <div class="detail-text">
                Statistik global (Hijau/Kuning/Merah)<br>
                Pengaturan bobot dinamis dengan slider (0-100%)<br>
                Tombol Simpan untuk trigger recalculation otomatis
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Dosen Wali Dashboard (/advisor/dosen)</div>
            <div class="detail-text">
                Daftar mahasiswa bimbingan yang sudah di-rank<br>
                Urutan: Merah (prioritas) → Kuning → Hijau<br>
                Statistik per kategori + tombol "Jalankan Analisis Terbaru"
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Mahasiswa Dashboard</div>
            <div class="detail-text">
                Kotak "Academic Insight" dengan kategori warna<br>
                Progress bar visual dari nilai V<br>
                Rekomendasi user-friendly
            </div>
        </div>
    </div>
    <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
        <strong>Akses:</strong> <span class="category-badge">Admin</span> <span class="category-badge">Dosen</span> <span class="category-badge">Mahasiswa</span>
    </p>
</div>

<!-- 6. Report Center -->
<div class="feature-section" id="reports">
    <div class="feature-title">
        <span class="feature-icon">📊</span>
        Report Center (Laporan Akademik)
    </div>
    <p class="feature-description">
        Pusat laporan terpadu dengan standar format resmi. Menghasilkan PDF untuk perwalian dan portfolio mahasiswa.
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">📋 Laporan Dosen Perwalian (PDF)</div>
            <div class="detail-text">
                <strong>Data:</strong> Jumlah mahasiswa, Status Perwalian (Sudah/Belum), Surat Peringkat (SP 1/2/3)<br>
                <strong>Perhitungan:</strong><br>
                • Sudah Perwalian = Mahasiswa dengan ≥1 konsultasi<br>
                • Belum Perwalian = 0 konsultasi<br>
                • SP 1 = IPK < 2.70, SP 2 = IPK < 2.50, SP 3 = IPK < 2.00<br>
                • Mahasiswa Perlu Perhatian = IPK < 3.00 atau IPS turun
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">👤 Laporan Portfolio Mahasiswa (PDF)</div>
            <div class="detail-text">
                <strong>Data:</strong> Biodata, Grafik IPS per semester, Tabel rekap nilai, IPK kumulatif, Prestasi, Catatan dosen<br>
                <strong>Akses:</strong> Personal per mahasiswa
            </div>
        </div>
    </div>
    <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
        <strong>Akses:</strong> <span class="category-badge">Admin</span> <span class="category-badge">Dosen</span> <span class="category-badge">Mahasiswa</span>
    </p>
</div>

<!-- 7. Data Master -->
<div class="feature-section" id="data-master">
    <div class="feature-title">
        <span class="feature-icon">🗂️</span>
        Data Master & Manajemen
    </div>
    <p class="feature-description">
        Sistem CRUD untuk data utama sistem: Mahasiswa, Dosen, Mata Kuliah dengan sinkronisasi otomatis.
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">👥 Data Mahasiswa</div>
            <div class="detail-text">CRUD Mahasiswa. Field: NIM, Nama, Prodi, Angkatan, Dosen Wali, Foto. Otomatis sync dengan tabel users untuk login</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">👨‍🏫 Data Dosen</div>
            <div class="detail-text">CRUD Dosen Wali. Field: NIDN, Nama, Jabatan. Sync dengan users (role=dosen). Hanya admin yang manage</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📚 Data Mata Kuliah</div>
            <div class="detail-text">CRUD Mata Kuliah. Field: Kode, Nama, SKS, Semester Default. Gunakan saat membuat KRS</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📅 Data Semester</div>
            <div class="detail-text">Manajemen semester/tahun akademik. Field: Urutan, Tahun Akademik, Periode (Ganjil/Genap)</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📝 Catatan Dosen Wali</div>
            <div class="detail-text">Dosen memberikan catatan/motivasi ke setiap mahasiswa. Tampil di Dashboard dengan urutan terbaru</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">🏆 Prestasi Mahasiswa</div>
            <div class="detail-text">Mahasiswa mendaftarkan prestasi (Akademik/Non-Akademik). Field: Nama Kegiatan, Jenis, Tempat, Tanggal, Pembimbing, Bukti File</div>
        </div>
    </div>
    <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
        <strong>Akses:</strong> <span class="category-badge">Admin</span> <span class="category-badge">Dosen</span> (limited)
    </p>
</div>

<!-- 8. Design System -->
<div class="feature-section" id="design">
    <div class="feature-title">
        <span class="feature-icon">🎨</span>
        Design System LUMINARA
    </div>
    <p class="feature-description">
        Filosofi desain "Antigravity" dengan glassmorphism, floating UI, dan color system modern.
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">🎯 Filosofi "Antigravity"</div>
            <div class="detail-text">
                • <strong>Floating UI:</strong> Drop-shadow & hover transform untuk kesan ringan<br>
                • <strong>Glassmorphism:</strong> backdrop-filter: blur() pada panel-panel<br>
                • <strong>Clean & Spacious:</strong> White-space generous, elemen tidak padat
            </div>
        </div>
    </div>
    
    <p style="margin: 1rem 0; font-weight: 600; color: var(--accent);">
        🎨 Palet Warna:
    </p>
    <div class="color-palette">
        <div class="color-item" style="background: #06b6d4; color: white;">
            <div style="font-weight: 700;">--primary</div>
            <div style="font-size: 0.85rem; margin-top: 0.5rem;">#06b6d4 (Cyan)</div>
            <div style="font-size: 0.8rem; margin-top: 0.25rem;">Aksen utama</div>
        </div>
        <div class="color-item" style="background: #14b8a6; color: white;">
            <div style="font-weight: 700;">--secondary</div>
            <div style="font-size: 0.85rem; margin-top: 0.5rem;">#14b8a6 (Tosca)</div>
            <div style="font-size: 0.8rem; margin-top: 0.25rem;">Gradien sekunder</div>
        </div>
        <div class="color-item" style="background: #0f172a; color: white;">
            <div style="font-weight: 700;">--accent</div>
            <div style="font-size: 0.85rem; margin-top: 0.5rem;">#0f172a (Deep Navy)</div>
            <div style="font-size: 0.8rem; margin-top: 0.25rem;">Dark accent</div>
        </div>
        <div class="color-item" style="background: #f8fafc; color: var(--ink); border: 2px solid #e5e7eb;">
            <div style="font-weight: 700;">--page</div>
            <div style="font-size: 0.85rem; margin-top: 0.5rem;">#f8fafc (Slate)</div>
            <div style="font-size: 0.8rem; margin-top: 0.25rem;">Background terang</div>
        </div>
    </div>
    
    <p style="margin: 1rem 0; font-weight: 600; color: var(--accent);">
        🧩 Komponen Utama:
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">📌 Sidebar (Nav)</div>
            <div class="detail-text">Pill-shaped items dengan glow effect (Box-Shadow Cyan) saat active</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">🔐 Halaman Login</div>
            <div class="detail-text">Deep Navy background + CSS Orbs (Cyan/Tosca/Biru) dengan blur(80px) dan animasi bergeser dinamis</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">⌨️ Input Field</div>
            <div class="detail-text">Dark transparent dengan glowing border Cyan + icon show/hide password (presisi aligned)</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📮 Grid Pesan</div>
            <div class="detail-text">CSS Grid (grid-template-columns: repeat(auto-fill, minmax(320px, 1fr))). Kartu dengan overflow-y: auto</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">📄 Paginasi Custom</div>
            <div class="detail-text">Inline styling dengan warna Cyan active, konsisten dengan brand identity</div>
        </div>
    </div>
</div>

<!-- 9. Security -->
<div class="feature-section" id="security">
    <div class="feature-title">
        <span class="feature-icon">🔒</span>
        Keamanan & Role-Based Access
    </div>
    <p class="feature-description">
        Multi-role authentication dengan kontrol akses berbasis role dan middleware perlindungan rute.
    </p>
    <div class="feature-details">
        <div class="detail-item">
            <div class="detail-label">🔑 Autentikasi</div>
            <div class="detail-text">Email + Password dengan hashing Laravel Hash::make(). Multi-role: Admin, Dosen, Mahasiswa</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">🛡️ Role-Based Access Control (RBAC)</div>
            <div class="detail-text">
                • <strong>Admin:</strong> Akses penuh semua modul<br>
                • <strong>Dosen:</strong> Input Nilai (mahasiswa wali), Pesan, Smart Advisor<br>
                • <strong>Mahasiswa:</strong> Dashboard, Pesan, Prestasi, Lihat Nilai
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">🔐 Middleware Protection</div>
            <div class="detail-text">auth middleware melindungi rute sensitif. guest middleware untuk halaman login. Role checking di setiap controller</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">👁️ Data Isolation</div>
            <div class="detail-text">Dosen hanya lihat mahasiswa wali mereka. Mahasiswa hanya akses data diri sendiri</div>
        </div>
    </div>
    
    <p style="margin: 1rem 0; font-weight: 600; color: var(--accent);">
        📧 Akun Default Setelah Seeder:
    </p>
    <table class="spec-table">
        <thead>
            <tr>
                <th>Role</th>
                <th>Email</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Mahasiswa</strong></td>
                <td>nim@luminara.id</td>
                <td>password</td>
            </tr>
            <tr>
                <td><strong>Dosen</strong></td>
                <td>dosen@example.com</td>
                <td>password</td>
            </tr>
            <tr>
                <td><strong>Admin</strong></td>
                <td>admin@example.com</td>
                <td>password</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Footer -->
<div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb; text-align: center; color: #666; font-size: 0.9rem;">
    <p>
        <strong>ERICK</strong> - Evaluasi Rekam Indeks, Capaian dan Konsultasi Akademik<br>
        Design System LUMINARA (2026) | Dibangun dengan Laravel + CSS Vanilla<br>
        <em>Dikembangkan untuk Ekosistem Akademik Masa Depan</em>
    </p>
</div>
@endsection
