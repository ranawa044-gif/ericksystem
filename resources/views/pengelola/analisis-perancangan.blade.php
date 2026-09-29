@extends('layouts.app')

@section('title', 'Analisis & Perancangan Sistem (UML & ERD)')

@push('styles')
<style>
    /* Styling Navigasi Tab & Desain Halaman */
    .analysis-tabs {
        display: flex;
        gap: 0.5rem;
        border-bottom: 2px solid var(--line);
        margin-bottom: 1.5rem;
        overflow-x: auto;
        padding-bottom: 2px;
    }
    .analysis-tab-btn {
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--muted);
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .analysis-tab-btn:hover {
        color: var(--primary-dark);
        background: rgba(6, 182, 212, 0.05);
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }
    .analysis-tab-btn.active {
        color: var(--primary-dark);
        border-bottom-color: var(--primary);
        background: rgba(6, 182, 212, 0.08);
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }

    .tab-content-panel {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }
    .tab-content-panel.active {
        display: block;
    }

    .diagram-card {
        background: #ffffff;
        border: 1px solid var(--line);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        padding: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .diagram-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .diagram-figure-badge {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.25rem 0.6rem;
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 6px;
        margin-bottom: 0.25rem;
    }

    .mermaid-viewport {
        background: #fcfdfe;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 1.5rem 1rem;
        overflow-x: auto;
        display: flex;
        justify-content: center;
        min-height: 200px;
    }

    .mermaid, .mermaid-target {
        width: 100%;
        text-align: center;
        display: flex;
        justify-content: center;
    }

    .copy-code-btn {
        background: #f8fafc;
        border: 1px solid var(--line);
        color: var(--ink);
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }
    .copy-code-btn:hover {
        background: #e2e8f0;
        color: var(--primary-dark);
    }

    .spec-table-box {
        margin-top: 1.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
    }

    /* Print Styles (Mode Cetak PDF/Kertas Laporan) */
    @media print {
        .sidebar, .top-header, .analysis-tabs, .no-print, .copy-code-btn {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .content-area {
            padding: 0 !important;
        }
        .tab-content-panel {
            display: block !important;
            page-break-after: always;
        }
        .diagram-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
            margin-bottom: 2rem !important;
        }
        body {
            background: #fff !important;
            color: #000 !important;
            font-size: 11pt !important;
        }
    }
</style>
@endpush

@section('content')
<div class="mb-6 flex justify-between items-start flex-wrap gap-4 no-print">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="badge badge-primary" style="font-size: 0.75rem;">REKAYASA PERANGKAT LUNAK</span>
            <span class="badge badge-secondary" style="font-size: 0.75rem;">STANDAR IEEE / SKRIPSI</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">Analisis & Perancangan Sistem (UML & ERD)</h1>
        <p class="text-muted text-sm mt-1">Dokumentasi diagram permodelan sistem E-RICK (Smart Academic Advisor) dan relasi basis data.</p>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="window.print()" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            <span>Cetak / Simpan PDF</span>
        </button>
        <button onclick="expandAllTabs()" class="btn btn-primary" id="toggleViewBtn" style="display: inline-flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
            <span>Tampilkan Semua Tab</span>
        </button>
    </div>
</div>

<!-- Quick System Highlights Cards -->
<div class="grid-4 mb-6 no-print">
    <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--primary);">
        <div class="text-xs text-muted font-semibold uppercase">Aktor Sistem</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">3 Aktor</div>
        <div class="text-xs text-muted">Admin, Dosen Wali, Mahasiswa</div>
    </div>
    <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--secondary);">
        <div class="text-xs text-muted font-semibold uppercase">Use Case Utama</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">16 Use Case</div>
        <div class="text-xs text-muted">Perwalian, SPK, KRS & Nilai</div>
    </div>
    <div class="card" style="margin-bottom: 0; border-left: 4px solid #6366f1;">
        <div class="text-xs text-muted font-semibold uppercase">Tabel Basis Data</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">12 Tabel</div>
        <div class="text-xs text-muted">MySQL (simnilai) + Cross PAMASESA</div>
    </div>
    <div class="card" style="margin-bottom: 0; border-left: 4px solid #f59e0b;">
        <div class="text-xs text-muted font-semibold uppercase">Metode SPK</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">TOPSIS</div>
        <div class="text-xs text-muted">C1-C5 Bobot Dinamis</div>
    </div>
</div>

<!-- Tab Navigasi -->
<div class="analysis-tabs no-print" id="tabsBar">
    <button class="analysis-tab-btn active" onclick="switchTab('tab-usecase')">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        1. Use Case Diagram
    </button>
    <button class="analysis-tab-btn" onclick="switchTab('tab-activity')">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
        2. Activity Diagram
    </button>
    <button class="analysis-tab-btn" onclick="switchTab('tab-sequence')">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
        3. Sequence Diagram
    </button>
    <button class="analysis-tab-btn" onclick="switchTab('tab-class')">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
        4. Class Diagram
    </button>
    <button class="analysis-tab-btn" onclick="switchTab('tab-erd')">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
        5. ERD & Kamus Data
    </button>
</div>

<!-- ========================================== -->
<!-- TAB 1: USE CASE DIAGRAM & SKENARIO         -->
<!-- ========================================== -->
<div id="tab-usecase" class="tab-content-panel active">
    <div class="diagram-card">
        <div class="diagram-header">
            <div>
                <span class="diagram-figure-badge">Gambar 3.1</span>
                <h3 class="font-bold text-lg text-slate-800 m-0">Use Case Diagram Sistem E-RICK</h3>
                <p class="text-xs text-muted mt-0.5">Memetakan interaksi fungsional antara Administrator, Dosen Wali, dan Mahasiswa terhadap modul sistem.</p>
            </div>
            <div>
                <button class="copy-code-btn" onclick="copyMermaid('mermaid-usecase')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Salin Kode Mermaid</span>
                </button>
            </div>
        </div>

        <div class="mermaid-viewport">
            <pre id="src-mermaid-usecase" style="display:none;">
graph LR
    %% Definisi Aktor
    Admin["👤 Administrator"]
    Dosen["👨‍🏫 Dosen Wali"]
    Mhs["🎓 Mahasiswa"]

    subgraph ERICK["Sistem E-RICK Smart Academic Advisor"]
        UC_Auth(["Login dan Autentikasi"])
        
        %% Kelompok Admin
        UC_Master(["Kelola Data Master Dosen, Mhs, Matkul"])
        UC_Nilai(["Kelola KRS dan Input Nilai"])
        UC_Bobot(["Atur Bobot Kriteria TOPSIS"])
        UC_GlobalSPK(["Lihat Analitik SPK Global"])
        UC_Backup(["Backup Database"])
        UC_Rekap(["Cetak Laporan dan Rekap"])

        %% Kelompok Dosen Wali
        UC_SmartAdv(["Smart Advisor TOPSIS Prioritas Bimbingan"])
        UC_RunSPK(["Jalankan Analisis SPK"])
        UC_Catatan(["Kelola Catatan Perwalian"])
        UC_BalasPesan(["Bimbingan dan Balas Pesan"])
        UC_MonPA(["Monitoring PA Mahasiswa PAMASESA"])

        %% Kelompok Mahasiswa
        UC_DashMhs(["Lihat Dashboard Akademik Mahasiswa"])
        UC_Prestasi(["Input dan Portofolio Prestasi"])
        UC_KirimPesan(["Konsultasi Chat ke Dosen Wali"])
        UC_Insight(["Lihat Rekomendasi Academic Insight"])
        UC_ViewPA(["Lihat Status Proyek Akhir PAMASESA"])
    end

    %% Relasi Admin
    Admin --> UC_Auth
    Admin --> UC_Master
    Admin --> UC_Nilai
    Admin --> UC_Bobot
    Admin --> UC_GlobalSPK
    Admin --> UC_Backup
    Admin --> UC_Rekap

    %% Relasi Dosen Wali
    Dosen --> UC_Auth
    Dosen --> UC_SmartAdv
    Dosen --> UC_RunSPK
    Dosen --> UC_Catatan
    Dosen --> UC_BalasPesan
    Dosen --> UC_MonPA

    %% Relasi Mahasiswa
    Mhs --> UC_Auth
    Mhs --> UC_DashMhs
    Mhs --> UC_Prestasi
    Mhs --> UC_KirimPesan
    Mhs --> UC_Insight
    Mhs --> UC_ViewPA

    %% Include
    UC_SmartAdv -.->|include| UC_RunSPK
    UC_DashMhs -.->|include| UC_Insight
    UC_DashMhs -.->|include| UC_ViewPA

    classDef default fill:#f8fafc,stroke:#cbd5e1,stroke-width:1.5px;
    classDef actor fill:#e0f2fe,stroke:#0284c7,stroke-width:2px,font-weight:bold;
    class Admin,Dosen,Mhs actor;
            </pre>
            <div class="mermaid-target" id="mermaid-usecase"></div>
        </div>

        <!-- Tabel Deskripsi Aktor & Skenario Use Case -->
        <div class="spec-table-box mt-4">
            <h4 class="font-bold text-sm text-slate-800 mb-2">Tabel 3.1 Definisi Aktor Sistem</h4>
            <div class="table-responsive">
                <table class="table text-sm" style="margin: 0;">
                    <thead>
                        <tr class="bg-slate-100">
                            <th style="width: 150px;">Aktor</th>
                            <th>Deskripsi Tanggung Jawab</th>
                            <th>Hak Akses Utama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-semibold text-slate-800">Administrator</td>
                            <td>Pengelola utama sistem akademik yang bertanggung jawab atas master data, kurikulum mata kuliah, dan bobot perhitungan SPK.</td>
                            <td>Master Dosen/Mhs/Matkul, Import Excel, Input Nilai, SPK Analytics, Backup DB.</td>
                        </tr>
                        <tr>
                            <td class="font-semibold text-slate-800">Dosen Wali</td>
                            <td>Dosen pembimbing akademik yang memantau performa studi mahasiswa bimbingannya, memberi catatan, dan memprioritaskan pendampingan.</td>
                            <td>Smart Advisor (TOPSIS), Chat Konsultasi, Catatan Perwalian, Monitoring Status PA (PAMASESA).</td>
                        </tr>
                        <tr>
                            <td class="font-semibold text-slate-800">Mahasiswa</td>
                            <td>Pengguna peserta didik yang memantau rekam nilai, mengajukan konsultasi, mendata prestasi, dan memantau progres PA.</td>
                            <td>Dashboard Akademik, Portofolio Prestasi, Konsultasi Chat, Academic Insight.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="spec-table-box mt-4">
            <h4 class="font-bold text-sm text-slate-800 mb-2">Tabel 3.2 Skenario Use Case Utama: Smart Academic Advisor (SPK TOPSIS)</h4>
            <div class="table-responsive">
                <table class="table text-sm" style="margin: 0;">
                    <tr>
                        <td class="font-semibold bg-slate-50" style="width: 180px;">Nomor Use Case</td>
                        <td>UC-08</td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-slate-50">Nama Use Case</td>
                        <td>Menjalankan Analisis Prioritas Pendampingan Mahasiswa (Smart Advisor)</td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-slate-50">Aktor Utama</td>
                        <td>Dosen Wali</td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-slate-50">Prekondisi</td>
                        <td>Dosen telah berhasil login dan memiliki mahasiswa perwalian dengan data nilai/KRS terisi.</td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-slate-50">Postkondisi</td>
                        <td>Peringkat preferensi TOPSIS ($V$) diperbarui dan kategori status (Hijau/Kuning/Merah) tersimpan di database `academic_predictions`.</td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-slate-50">Alur Utama (Main Flow)</td>
                        <td>
                            1. Dosen membuka menu Smart Advisor.<br/>
                            2. Sistem menampilkan daftar ranking prioritas mahasiswa terkini.<br/>
                            3. Dosen menekan tombol "Jalankan Analisis Terbaru".<br/>
                            4. `AcademicDecisionService` mengekstrak kriteria C1 (IPK), C2 (Tren IPS), C3 (Nilai Bermasalah), C4 (Konsultasi), dan C5 (Prestasi).<br/>
                            5. Sistem melakukan normalisasi matriks $R$, pembobotan $Y$, penentuan solusi ideal $A^+ / A^-$, dan jarak euclidean $D^+ / D^-$.<br/>
                            6. Sistem menghitung nilai preferensi $V_i = \frac{D_i^-}{D_i^+ + D_i^-}$.<br/>
                            7. Sistem mengelompokkan kategori (Aman, Monitoring, Pendampingan Khusus) dan menyimpan rekomendasi.<br/>
                            8. Sistem menyajikan hasil peringkat terbaru dengan urutan skor terendah di posisi teratas.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- TAB 2: ACTIVITY DIAGRAM                    -->
<!-- ========================================== -->
<div id="tab-activity" class="tab-content-panel">
    <!-- Activity 1: Konsultasi & Perwalian -->
    <div class="diagram-card">
        <div class="diagram-header">
            <div>
                <span class="diagram-figure-badge">Gambar 3.2</span>
                <h3 class="font-bold text-lg text-slate-800 m-0">Activity Diagram: Alur Konsultasi & Catatan Perwalian</h3>
                <p class="text-xs text-muted mt-0.5">Menggambarkan interaksi dua arah antara mahasiswa bimbingan dan dosen wali.</p>
            </div>
            <div>
                <button class="copy-code-btn" onclick="copyMermaid('mermaid-act-konsultasi')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Salin Kode Mermaid</span>
                </button>
            </div>
        </div>
        <div class="mermaid-viewport">
            <pre id="src-mermaid-act-konsultasi" style="display:none;">
flowchart TD
    Start((Mulai)) --> MhsLogin["Mahasiswa Login ke Portal"]
    MhsLogin --> BukaKonsul["Buka Menu Konsultasi"]
    BukaKonsul --> TulisPesan["Ketik Pesan atau Pertanyaan Perwalian"]
    TulisPesan --> KirimPesan["Kirim Pesan"]

    KirimPesan --> SimpanPesan[("Simpan ke pesan_mahasiswas")]
    SimpanPesan --> NotifDosen["Notifikasi Pesan Masuk ke Dosen"]

    NotifDosen --> DosenLogin["Dosen Wali Login"]
    DosenLogin --> BukaInbox["Buka Inbox Pesan Mahasiswa"]
    BukaInbox --> BacaPesan["Baca Pesan Mahasiswa"]
    
    BacaPesan --> BalasPesan["Ketik dan Kirim Balasan Konsultasi"]
    BalasPesan --> SimpanBalasan[("Simpan Balasan Pesan")]
    
    BalasPesan --> BuatCatatanPerwalian{"Perlu Catatan Resmi?"}
    BuatCatatanPerwalian -->|Ya| InputCatatan["Input Catatan di Menu Catatan Perwalian"]
    InputCatatan --> SimpanCatatan[("Simpan ke catatan_dosen_walis")]
    SimpanCatatan --> MhsBaca["Mahasiswa Membaca Balasan dan Catatan di Dashboard"]
    BuatCatatanPerwalian -->|Tidak| MhsBaca
    
    MhsBaca --> Selesai((Selesai))

    classDef default fill:#f8fafc,stroke:#94a3b8,stroke-width:1.5px;
    classDef storage fill:#fef3c7,stroke:#d97706,stroke-width:1.5px;
    classDef decision fill:#e0f2fe,stroke:#0284c7,stroke-width:1.5px;
    class SimpanPesan,SimpanBalasan,SimpanCatatan storage;
    class BuatCatatanPerwalian decision;
            </pre>
            <div class="mermaid-target" id="mermaid-act-konsultasi"></div>
        </div>
    </div>

    <!-- Activity 2: SPK TOPSIS -->
    <div class="diagram-card">
        <div class="diagram-header">
            <div>
                <span class="diagram-figure-badge">Gambar 3.3</span>
                <h3 class="font-bold text-lg text-slate-800 m-0">Activity Diagram: Perhitungan SPK TOPSIS Pendampingan Akademik</h3>
                <p class="text-xs text-muted mt-0.5">Alur matematis penentuan prioritas pendampingan mahasiswa di AcademicDecisionService.</p>
            </div>
            <div>
                <button class="copy-code-btn" onclick="copyMermaid('mermaid-act-spk')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Salin Kode Mermaid</span>
                </button>
            </div>
        </div>
        <div class="mermaid-viewport">
            <pre id="src-mermaid-act-spk" style="display:none;">
flowchart TD
    Start((Mulai)) --> TriggerCalc["Dosen Wali Klik Jalankan Analisis"]
    TriggerCalc --> TarikBobot[("Tarik Bobot Kriteria C1-C5 dari spk_criteria_weights")]
    TarikBobot --> TarikDataMhs[("Tarik Data Akademik Mahasiswa: KRS, Nilai, Pesan, Prestasi")]
    
    TarikDataMhs --> MatriksX["Bentuk Matriks Keputusan X berukuran m x n"]
    MatriksX --> NormalisasiR["Hitung Normalisasi Matriks R"]
    NormalisasiR --> MatriksY["Hitung Matriks Terbobot Y"]
    
    MatriksY --> TentukanSolusiIdeal["Tentukan Solusi Ideal Positif A+ dan Negatif A-"]
    TentukanSolusiIdeal --> HitungJarak["Hitung Jarak Euclidean D+ dan D- setiap alternatif"]
    HitungJarak --> HitungPreferensi["Hitung Nilai Preferensi Vi"]
    
    HitungPreferensi --> Klasifikasi{"Kategorisasi Skor Preferensi V"}
    Klasifikasi -->|V kurang dari 0.40| KatMerah["Kategori MERAH: Perlu Pendampingan Khusus"]
    Klasifikasi -->|V antara 0.40 sampai 0.65| KatKuning["Kategori KUNING: Perlu Monitoring"]
    Klasifikasi -->|V lebih dari atau sama dengan 0.65| KatHijau["Kategori HIJAU: Kondisi Akademik Aman"]
    
    KatMerah --> SimpanPrediksi[("Simpan Hasil ke academic_predictions")]
    KatKuning --> SimpanPrediksi
    KatHijau --> SimpanPrediksi
    
    SimpanPrediksi --> RenderRanking["Tampilkan Peringkat Urut Skor V Terendah ke Dosen"]
    RenderRanking --> Selesai((Selesai))

    classDef default fill:#f8fafc,stroke:#94a3b8,stroke-width:1.5px;
    classDef storage fill:#fef3c7,stroke:#d97706,stroke-width:1.5px;
    classDef decision fill:#fee2e2,stroke:#ef4444,stroke-width:1.5px;
    class TarikBobot,TarikDataMhs,SimpanPrediksi storage;
    class Klasifikasi decision;
            </pre>
            <div class="mermaid-target" id="mermaid-act-spk"></div>
        </div>
    </div>

    <!-- Activity 3: Integrasi PAMASESA -->
    <div class="diagram-card">
        <div class="diagram-header">
            <div>
                <span class="diagram-figure-badge">Gambar 3.4</span>
                <h3 class="font-bold text-lg text-slate-800 m-0">Activity Diagram: Alur Integrasi Lintas Sistem PAMASESA</h3>
                <p class="text-xs text-muted mt-0.5">Mekanisme query toleran gangguan (fault-tolerant cross-database) antara E-RICK dan PAMASESA.</p>
            </div>
            <div>
                <button class="copy-code-btn" onclick="copyMermaid('mermaid-act-pamasesa')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Salin Kode Mermaid</span>
                </button>
            </div>
        </div>
        <div class="mermaid-viewport">
            <pre id="src-mermaid-act-pamasesa" style="display:none;">
flowchart TD
    Start((Mulai)) --> RequestView["Pengguna Membuka Dashboard Mahasiswa atau Smart Advisor"]
    RequestView --> CekKoneksi{"Panggil PamasesaService::isAvailable"}
    
    CekKoneksi -->|Koneksi Gagal / DB Offline| FallbackStatus["Return Status Layanan Tidak Terhubung"]
    FallbackStatus --> RenderNotice["Tampilkan Badge Netral Tanpa Crash 500"]
    
    CekKoneksi -->|Koneksi Berhasil| QueryMasesa[("Cari NIM di Database PAMASESA")]
    QueryMasesa --> CekMhsAda{"NIM Terdaftar di PAMASESA?"}
    
    CekMhsAda -->|Tidak| StatusNotReg["Set Status: Belum Terdaftar di PAMASESA"]
    CekMhsAda -->|Ya| QueryPA[("Ambil Data dari masesa.final_projects")]
    
    QueryPA --> CekJudulAda{"Sudah Mengajukan Judul?"}
    CekJudulAda -->|Tidak| StatusNotSubmitted["Set Status: Belum Mengajukan Judul"]
    StatusNotSubmitted --> RenderButton["Tampilkan Ajakan Mengajukan Judul PA"]
    
    CekJudulAda -->|Ya| AmbilDetail[("Ambil Pembimbing dan Logbook")]
    AmbilDetail --> FormatRingkasan["Hitung Kuota Sempro dan Sidang, Format Badge"]
    FormatRingkasan --> RenderWidget["Tampilkan Widget Live Status PA dan Tombol Portal"]
    
    RenderNotice --> EndNode((Selesai))
    RenderButton --> EndNode
    RenderWidget --> EndNode

    classDef default fill:#f8fafc,stroke:#94a3b8,stroke-width:1.5px;
    classDef storage fill:#fef3c7,stroke:#d97706,stroke-width:1.5px;
    classDef decision fill:#e0f2fe,stroke:#0284c7,stroke-width:1.5px;
    class QueryMasesa,QueryPA,AmbilDetail storage;
    class CekKoneksi,CekMhsAda,CekJudulAda decision;
            </pre>
            <div class="mermaid-target" id="mermaid-act-pamasesa"></div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- TAB 3: SEQUENCE DIAGRAM                    -->
<!-- ========================================== -->
<div id="tab-sequence" class="tab-content-panel">
    <!-- Sequence 1: SPK TOPSIS -->
    <div class="diagram-card">
        <div class="diagram-header">
            <div>
                <span class="diagram-figure-badge">Gambar 3.5</span>
                <h3 class="font-bold text-lg text-slate-800 m-0">Sequence Diagram: Eksekusi Analisis SPK TOPSIS</h3>
                <p class="text-xs text-muted mt-0.5">Urutan pengiriman pesan antar-komponen MVC dan Service saat kalkulasi pendampingan dijalankan.</p>
            </div>
            <div>
                <button class="copy-code-btn" onclick="copyMermaid('mermaid-seq-spk')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Salin Kode Mermaid</span>
                </button>
            </div>
        </div>
        <div class="mermaid-viewport">
            <pre id="src-mermaid-seq-spk" style="display:none;">
sequenceDiagram
    autonumber
    actor Dosen as Dosen Wali
    participant View as View (advisor/dosen)
    participant Ctrl as SmartAdvisorController
    participant Svc as AcademicDecisionService
    participant Weight as SpkCriteriaWeight
    participant Mhs as Mahasiswa (Model)
    participant Pred as AcademicPrediction

    Dosen->>View: Buka Halaman Smart Advisor
    View->>Ctrl: GET /advisor/dosen
    Ctrl->>Pred: Ambil riwayat prediksi terakhir
    Pred-->>Ctrl: Data ranking
    Ctrl-->>View: Render tabel prioritas
    
    Note over Dosen,View: Dosen memicu update analisis terbaru
    Dosen->>View: Klik Jalankan Analisis Terbaru
    View->>Ctrl: POST /advisor/dosen/run
    Ctrl->>Svc: calculateAll(dosen_id)
    
    activate Svc
    Svc->>Weight: Ambil bobot kriteria C1 sampai C5
    Weight-->>Svc: Array bobot
    
    Svc->>Mhs: Ambil mahasiswa bimbingan dan relasi
    Mhs-->>Svc: Data akademik lengkap
    
    Svc->>Svc: Normalisasi Matriks R
    Svc->>Svc: Hitung Matriks Terbobot Y
    Svc->>Svc: Tentukan Solusi Ideal Positif dan Negatif
    Svc->>Svc: Hitung Jarak Euclidean D+ dan D-
    Svc->>Svc: Hitung Nilai Preferensi Vi
    Svc->>Svc: Kelompokkan Kategori Hijau, Kuning, Merah
    
    Svc->>Pred: updateOrCreate(mahasiswa_id, score, category, details)
    Pred-->>Svc: Status tersimpan
    deactivate Svc

    Svc-->>Ctrl: Selesai
    Ctrl-->>View: Redirect back + Flash Success
    View-->>Dosen: Tampilkan daftar ranking prioritas terbaru
            </pre>
            <div class="mermaid-target" id="mermaid-seq-spk"></div>
        </div>
    </div>

    <!-- Sequence 2: Integrasi PAMASESA -->
    <div class="diagram-card">
        <div class="diagram-header">
            <div>
                <span class="diagram-figure-badge">Gambar 3.6</span>
                <h3 class="font-bold text-lg text-slate-800 m-0">Sequence Diagram: Integrasi Data Proyek Akhir (PAMASESA)</h3>
                <p class="text-xs text-muted mt-0.5">Alur penarikan data lintas database (cross-connection) dari sistem PAMASESA.</p>
            </div>
            <div>
                <button class="copy-code-btn" onclick="copyMermaid('mermaid-seq-pamasesa')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Salin Kode Mermaid</span>
                </button>
            </div>
        </div>
        <div class="mermaid-viewport">
            <pre id="src-mermaid-seq-pamasesa" style="display:none;">
sequenceDiagram
    autonumber
    actor User as Pengguna (Mhs atau Dosen)
    participant View as DashboardView
    participant Ctrl as DashboardMahasiswaController
    participant Svc as PamasesaIntegrationService
    participant DBMasesa as Database PAMASESA

    User->>View: Akses Dashboard Mahasiswa
    View->>Ctrl: show(Request, nim)
    Ctrl->>Svc: getStudentPaSummary(nim)
    
    activate Svc
    Svc->>Svc: isAvailable() cek koneksi DB pamasesa
    
    alt Koneksi Tersedia
        Svc->>DBMasesa: SELECT * FROM students WHERE nim = ?
        DBMasesa-->>Svc: Data Student
        
        alt Student Memiliki Proyek Akhir
            Svc->>DBMasesa: SELECT * FROM final_projects WHERE student_id = ?
            DBMasesa-->>Svc: Data Final Project (Judul, Status)
            Svc->>DBMasesa: SELECT supervisors dan progress_logs
            DBMasesa-->>Svc: Data Pembimbing dan Logbook
            Svc-->>Ctrl: Array Summary (has_pa: true, judul, status)
        else Belum Mengajukan Judul
            Svc-->>Ctrl: Array Summary (has_pa: false, status: NOT_SUBMITTED)
        end
    else Database PAMASESA Offline
        Svc-->>Ctrl: Array Summary (is_connected: false, status: OFFLINE)
    end
    deactivate Svc

    Ctrl-->>View: Render View dengan variabel $paSummary
    View-->>User: Tampilkan Widget Proyek Akhir PAMASESA
            </pre>
            <div class="mermaid-target" id="mermaid-seq-pamasesa"></div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- TAB 4: CLASS DIAGRAM                       -->
<!-- ========================================== -->
<div id="tab-class" class="tab-content-panel">
    <div class="diagram-card">
        <div class="diagram-header">
            <div>
                <span class="diagram-figure-badge">Gambar 3.7</span>
                <h3 class="font-bold text-lg text-slate-800 m-0">Class Diagram Arsitektur Sistem E-RICK</h3>
                <p class="text-xs text-muted mt-0.5">Memetakan struktur kelas Controller, Service Layer, dan Model Eloquent ORM di Laravel.</p>
            </div>
            <div>
                <button class="copy-code-btn" onclick="copyMermaid('mermaid-class')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Salin Kode Mermaid</span>
                </button>
            </div>
        </div>
        <div class="mermaid-viewport">
            <pre id="src-mermaid-class" style="display:none;">
classDiagram
    %% Model Layer
    class User {
        +int id
        +string name
        +string email
        +string password
        +string role
        +int mahasiswa_id
        +int dosen_id
        +mahasiswa() BelongsTo
        +dosen() BelongsTo
        +isPengelola() bool
    }

    class Mahasiswa {
        +int id
        +string nim
        +string nama
        +string program_studi
        +string angkatan
        +int dosen_wali_id
        +dosenWali() BelongsTo
        +krs() HasMany
        +catatanDosenWali() HasMany
        +pesanKeMahasiswa() HasMany
        +prestasi() HasMany
    }

    class Dosen {
        +int id
        +string nidn
        +string nama
        +string jabatan
        +mahasiswaBimbingan() HasMany
        +user() HasOne
    }

    class Krs {
        +int id
        +int mahasiswa_id
        +int mata_kuliah_id
        +int semester_id
        +nilai() HasOne
        +mataKuliah() BelongsTo
        +semester() BelongsTo
    }

    class Nilai {
        +int id
        +int krs_id
        +float nilai_angka
        +string nilai_huruf
        +float bobot
        +bool status_lulus
    }

    class SpkCriteriaWeight {
        +int id
        +string code
        +string name
        +float weight
        +string type
    }

    class AcademicPrediction {
        +int id
        +int mahasiswa_id
        +float score
        +string category
        +string recommendation
        +json details
        +mahasiswa() BelongsTo
    }

    %% Service Layer
    class AcademicDecisionService {
        +calculateAll(dosen_id) array
        +normalizeMatrix(matrix) array
        +weightedMatrix(norm, weights) array
        +idealSolutions(weighted, types) array
        +euclideanDistances(weighted, ideal) array
        +preferenceScores(dPlus, dMinus) array
    }

    class PamasesaIntegrationService {
        +isAvailable() bool
        +getStudentPaSummary(nim) array
        +getBatchPaSummary(nims) Collection
        +getStatusMeta(status) array
    }

    %% Controller Layer
    class SmartAdvisorController {
        -advisorService: AcademicDecisionService
        -pamasesaService: PamasesaIntegrationService
        +indexDosen() View
        +runAnalysisDosen() RedirectResponse
        +indexAdmin() View
        +updateWeights(Request) RedirectResponse
    }

    class DashboardMahasiswaController {
        -pamasesaService: PamasesaIntegrationService
        +show(Request, nim) View
        +hitungIndeksPrestasi(krs) float
    }

    %% Relasi Antar Kelas
    User --> Mahasiswa : 1..1
    User --> Dosen : 1..1
    Mahasiswa --> Dosen : dosen_wali_id
    Mahasiswa --> Krs : has_many
    Krs --> Nilai : has_one
    Mahasiswa --> AcademicPrediction : has_one

    SmartAdvisorController ..> AcademicDecisionService : uses
    SmartAdvisorController ..> PamasesaIntegrationService : uses
    DashboardMahasiswaController ..> PamasesaIntegrationService : uses
    AcademicDecisionService ..> SpkCriteriaWeight : reads
    AcademicDecisionService ..> AcademicPrediction : writes
            </pre>
            <div class="mermaid-target" id="mermaid-class"></div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- TAB 5: ERD & KAMUS DATA                    -->
<!-- ========================================== -->
<div id="tab-erd" class="tab-content-panel">
    <div class="diagram-card">
        <div class="diagram-header">
            <div>
                <span class="diagram-figure-badge">Gambar 3.8</span>
                <h3 class="font-bold text-lg text-slate-800 m-0">Entity Relationship Diagram (ERD Crow's Foot)</h3>
                <p class="text-xs text-muted mt-0.5">Struktur entitas tabel MySQL database simnilai beserta relasi virtual ke PAMASESA.</p>
            </div>
            <div>
                <button class="copy-code-btn" onclick="copyMermaid('mermaid-erd')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span>Salin Kode Mermaid</span>
                </button>
            </div>
        </div>
        <div class="mermaid-viewport">
            <pre id="src-mermaid-erd" style="display:none;">
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        string password
        string role
        bigint mahasiswa_id FK
        bigint dosen_id FK
    }

    DOSENS {
        bigint id PK
        string nidn UK
        string nama
        string jabatan
    }

    MAHASISWAS {
        bigint id PK
        string nim UK
        string nama
        string program_studi
        string angkatan
        bigint dosen_wali_id FK
        string foto
    }

    SEMESTERS {
        bigint id PK
        string nama
        int urutan
    }

    MATA_KULIAHS {
        bigint id PK
        string kode UK
        string nama
        int sks
        int semester_default
    }

    KRS {
        bigint id PK
        bigint mahasiswa_id FK
        bigint mata_kuliah_id FK
        bigint semester_id FK
    }

    NILAIS {
        bigint id PK
        bigint krs_id FK
        decimal nilai_angka
        string nilai_huruf
        decimal bobot
        boolean status_lulus
    }

    CATATAN_DOSEN_WALIS {
        bigint id PK
        bigint mahasiswa_id FK
        bigint dosen_id FK
        date tanggal
        text catatan
    }

    PESAN_MAHASISWAS {
        bigint id PK
        bigint mahasiswa_id FK
        string pengirim
        text pesan
        boolean dibaca
    }

    PRESTASI_MAHASISWAS {
        bigint id PK
        bigint mahasiswa_id FK
        string judul
        string tingkat
        int tahun
        date tanggal
        string sertifikat
    }

    SPK_CRITERIA_WEIGHTS {
        bigint id PK
        string code UK
        string name
        decimal weight
        string type
    }

    ACADEMIC_PREDICTIONS {
        bigint id PK
        bigint mahasiswa_id FK
        decimal score
        string category
        text recommendation
        json details
    }

    %% Virtual Table PAMASESA
    PAMASESA_FINAL_PROJECTS {
        bigint id PK
        string nim_student FK
        text title
        string status
        string proposed_supervisor
    }

    %% Relasi
    DOSENS ||--o{ MAHASISWAS : "membimbing sebagai wali"
    DOSENS ||--o| USERS : "memiliki akun"
    MAHASISWAS ||--o| USERS : "memiliki akun"
    
    MAHASISWAS ||--o{ KRS : "mengambil"
    SEMESTERS ||--o{ KRS : "memuat"
    MATA_KULIAHS ||--o{ KRS : "terdaftar dalam"
    KRS ||--o| NILAIS : "memiliki hasil"

    MAHASISWAS ||--o{ CATATAN_DOSEN_WALIS : "menerima catatan"
    DOSENS ||--o{ CATATAN_DOSEN_WALIS : "memberikan catatan"

    MAHASISWAS ||--o{ PESAN_MAHASISWAS : "melakukan konsultasi"
    MAHASISWAS ||--o{ PRESTASI_MAHASISWAS : "mencatatkan"
    MAHASISWAS ||--o| ACADEMIC_PREDICTIONS : "memiliki evaluasi SPK"

    MAHASISWAS ||--o| PAMASESA_FINAL_PROJECTS : "relasi via NIM lintas database"
            </pre>
            <div class="mermaid-target" id="mermaid-erd"></div>
        </div>

        <!-- Kamus Data Lengkap -->
        <div class="spec-table-box mt-4">
            <h4 class="font-bold text-sm text-slate-800 mb-2">Tabel 3.3 Kamus Data (Data Dictionary) Tabel Utama</h4>
            <div class="table-responsive">
                <table class="table text-sm" style="margin: 0;">
                    <thead>
                        <tr class="bg-slate-100">
                            <th>Nama Tabel</th>
                            <th>Primary Key</th>
                            <th>Foreign Key</th>
                            <th>Fungsi / Deskripsi Penyimpanan Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">users</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">mahasiswa_id, dosen_id</td>
                            <td>Menyimpan autentikasi login (email, hash password) dengan role Admin, Dosen, atau Mahasiswa.</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">dosens</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">-</td>
                            <td>Menyimpan profil tenaga pengajar (NIDN, Nama, Jabatan Akademik).</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">mahasiswas</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">dosen_wali_id</td>
                            <td>Menyimpan identitas mahasiswa (NIM, Nama, Prodi, Angkatan, Foto Profil) serta ID dosen wali pembimbingnya.</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">krs</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">mahasiswa_id, mata_kuliah_id, semester_id</td>
                            <td>Tabel transaksi pengambilan mata kuliah oleh mahasiswa pada semester tertentu.</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">nilais</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">krs_id</td>
                            <td>Menyimpan penilaian mata kuliah (nilai angka 0-100, huruf A-E, bobot 0-4, status kelulusan).</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">catatan_dosen_walis</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">mahasiswa_id, dosen_id</td>
                            <td>Mendokumentasikan berita acara atau catatan resmi bimbingan perwalian berkala.</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">pesan_mahasiswas</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">mahasiswa_id</td>
                            <td>Log percakapan konsultasi perwalian dua arah antara mahasiswa dan dosen wali.</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">prestasi_mahasiswas</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">mahasiswa_id</td>
                            <td>Portofolio capaian lomba, kejuaraan, atau rekognisi mahasiswa beserta file sertifikat.</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">spk_criteria_weights</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">-</td>
                            <td>Konfigurasi persentase bobot dinamis kriteria TOPSIS (C1: IPK 35%, C2: Tren IPS 25%, C3: Nilai Bermasalah 20%, C4: Konsultasi 10%, C5: Prestasi 10%).</td>
                        </tr>
                        <tr>
                            <td class="font-mono font-semibold text-slate-800">academic_predictions</td>
                            <td class="font-mono text-xs">id</td>
                            <td class="font-mono text-xs">mahasiswa_id</td>
                            <td>Menyimpan hasil kalkulasi SPK TOPSIS (skor preferensi $V$, kategori Hijau/Kuning/Merah, dan narasi rekomendasi tindakan).</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifikasi Copy -->
<div id="copyToast" style="display:none; position: fixed; bottom: 24px; right: 24px; background: #0f172a; color: white; padding: 12px 20px; border-radius: 8px; font-size: 0.875rem; z-index: 9999; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
    ✓ Kode Mermaid berhasil disalin ke clipboard!
</div>

@endsection

@push('scripts')
<!-- Mermaid JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    // Konfigurasi Mermaid
    mermaid.initialize({
        startOnLoad: false,
        theme: 'neutral',
        themeVariables: {
            fontFamily: 'Inter, sans-serif',
            fontSize: '13px',
            primaryColor: '#e0f2fe',
            primaryBorderColor: '#0284c7',
            lineColor: '#64748b'
        },
        flowchart: {
            curve: 'basis',
            useMaxWidth: false
        },
        securityLevel: 'loose'
    });

    let renderCounter = 0;

    // Fungsi Render Diagram untuk panel/tab tertentu
    async function renderTabDiagrams(panel) {
        if (!panel) return;

        const targets = panel.querySelectorAll('.mermaid-target:not([data-rendered="true"])');
        for (const target of targets) {
            const srcEl = document.getElementById('src-' + target.id);
            if (!srcEl) continue;

            const rawCode = srcEl.textContent.trim();
            if (!rawCode) continue;

            renderCounter++;
            const renderId = 'svg_gen_' + renderCounter + '_' + Math.random().toString(36).substring(2, 7);

            try {
                const { svg } = await mermaid.render(renderId, rawCode);
                target.innerHTML = svg;
                target.setAttribute('data-rendered', 'true');
            } catch (err) {
                console.error('Mermaid render error pada ' + target.id + ':', err);
                const errMsg = (err.message || err.str || 'Sintaks tidak valid').replace(/</g, '&lt;');
                target.innerHTML = `
                    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:1.25rem; text-align:center; max-width:600px; margin:1rem auto;">
                        <div style="font-weight:700; color:#b91c1c; font-size:0.95rem; margin-bottom:0.25rem;">Gagal Merender Diagram (${target.id})</div>
                        <div style="font-size:0.8rem; color:#64748b; margin-bottom:0.75rem;">${errMsg}</div>
                        <button onclick="retryRender('${target.id}')" class="btn btn-secondary" style="font-size:0.75rem; padding:0.35rem 0.85rem;">Coba Render Ulang</button>
                    </div>
                `;
            }
        }
    }

    // Fungsi coba ulang jika perlu
    async function retryRender(elementId) {
        const target = document.getElementById(elementId);
        if (!target) return;
        target.removeAttribute('data-rendered');
        const parentPanel = target.closest('.tab-content-panel');
        if (parentPanel) {
            await renderTabDiagrams(parentPanel);
        }
    }

    // Inisialisasi awal saat dokumen selesai dimuat
    document.addEventListener('DOMContentLoaded', () => {
        const initialPanel = document.querySelector('.tab-content-panel.active');
        if (initialPanel) {
            renderTabDiagrams(initialPanel);
        }
    });

    // Logika Navigasi Tab
    async function switchTab(tabId) {
        // Hapus kelas aktif dari semua tombol
        document.querySelectorAll('.analysis-tab-btn').forEach(btn => btn.classList.remove('active'));
        // Sembunyikan semua panel
        document.querySelectorAll('.tab-content-panel').forEach(panel => panel.classList.remove('active'));
        
        // Aktifkan panel target
        const targetPanel = document.getElementById(tabId);
        if (targetPanel) {
            targetPanel.classList.add('active');
        }

        // Tandai tombol aktif
        const activeBtn = Array.from(document.querySelectorAll('.analysis-tab-btn')).find(btn => {
            const oc = btn.getAttribute('onclick') || '';
            return oc.includes(tabId);
        });
        if (activeBtn) {
            activeBtn.classList.add('active');
        }

        // Render diagram yang ada di tab target setelah panel terlihat
        if (targetPanel) {
            await renderTabDiagrams(targetPanel);
        }
    }

    // Toggle Tampilkan Semua Tab (Mode Cetak / Baca Sekaligus)
    let isAllExpanded = false;
    async function expandAllTabs() {
        isAllExpanded = !isAllExpanded;
        const btn = document.getElementById('toggleViewBtn');
        const panels = document.querySelectorAll('.tab-content-panel');
        const tabsBar = document.getElementById('tabsBar');

        if (isAllExpanded) {
            panels.forEach(p => p.classList.add('active'));
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg><span>Kembalikan Mode Tab</span>`;
            tabsBar.style.display = 'none';

            for (const panel of panels) {
                await renderTabDiagrams(panel);
            }
        } else {
            panels.forEach((p, idx) => {
                if (idx === 0) p.classList.add('active');
                else p.classList.remove('active');
            });
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg><span>Tampilkan Semua Tab</span>`;
            tabsBar.style.display = 'flex';
        }
    }

    // Fungsi Salin Kode Mermaid langsung dari elemen <pre id="src-...">
    function copyMermaid(elementId) {
        const srcEl = document.getElementById('src-' + elementId);
        if (!srcEl) return;

        const codeText = srcEl.textContent.trim();

        navigator.clipboard.writeText(codeText).then(() => {
            const toast = document.getElementById('copyToast');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 2500);
        }).catch(err => {
            console.error('Gagal menyalin kode:', err);
        });
    }
</script>
@endpush
