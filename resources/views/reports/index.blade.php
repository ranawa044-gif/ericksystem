@extends('layouts.app')

@section('title', 'Report Center')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2 text-gray-800">Academic Report Center</h1>
    <p class="text-muted">Pusat pelaporan data akademik E-RICK.</p>
</div>

@if(session('error'))
    <div class="alert alert-danger mb-4" style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 4px;">
        {{ session('error') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">

    @if(auth()->user()->role === 'mahasiswa')
        <!-- STUDENT PORTFOLIO -->
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <div style="background: #eff6ff; padding: 12px; border-radius: 50%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#3b82f6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <h3 class="font-semibold text-lg m-0">Student Portfolio</h3>
            </div>
            <p class="text-sm text-muted mb-4">Export dokumen portofolio akademik lengkap termasuk riwayat nilai, prestasi, dan konsultasi.</p>
            <a href="{{ route('reports.student_portfolio.pdf') }}?t={{ time() }}" class="btn btn-primary w-full text-center" target="_blank">Export PDF</a>
        </div>
    @endif

    @if(auth()->user()->role === 'dosen')
        <!-- LAPORAN PERWALIAN DOSEN -->
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <div style="background: #fdf2f8; padding: 12px; border-radius: 50%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#ec4899"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h3 class="font-semibold text-lg m-0">Laporan Perwalian</h3>
            </div>
            <p class="text-sm text-muted mb-4">Rekapitulasi mahasiswa perwalian, rata-rata IPK, serta daftar mahasiswa yang perlu perhatian.</p>
            <a href="{{ route('reports.dosen_perwalian.preview') }}" class="btn btn-primary w-full text-center">Buka Preview & Export</a>
        </div>
    @endif

    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
        <!-- ACADEMIC RISK REPORT -->
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <div style="background: #fef2f2; padding: 12px; border-radius: 50%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#ef4444"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="font-semibold text-lg m-0">Academic Risk</h3>
            </div>
            <p class="text-sm text-muted mb-4">Daftar mahasiswa yang memerlukan perhatian (IPK di bawah 3.00 atau tren IPS menurun).</p>
            <a href="{{ route('reports.academic_risk.pdf') }}" class="btn btn-primary w-full text-center" target="_blank" style="background: #ef4444; border-color: #ef4444;">Export PDF</a>
        </div>
    @endif

    @if(auth()->user()->role === 'admin')
        <!-- PRESTASI REPORT -->
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <div style="background: #fffbeb; padding: 12px; border-radius: 50%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#f59e0b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                </div>
                <h3 class="font-semibold text-lg m-0">Prestasi Report</h3>
            </div>
            <p class="text-sm text-muted mb-4">Filter dan ekspor data prestasi mahasiswa untuk laporan institusi.</p>
            
            <form action="{{ route('reports.prestasi.export') }}" method="GET">
                <div class="mb-3">
                    <label class="text-sm font-medium">Tahun</label>
                    <select name="tahun" class="form-control mt-1">
                        <option value="">Semua Tahun</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label class="text-sm font-medium">Tingkat</label>
                    <select name="tingkat" class="form-control mt-1">
                        <option value="">Semua Tingkat</option>
                        <option value="Internasional">Internasional</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Lokal/Universitas">Lokal/Universitas</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="text-sm font-medium">Kategori</label>
                    <select name="kategori" class="form-control mt-1">
                        <option value="">Semua Kategori</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Non-Akademik">Non-Akademik</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" name="format" value="pdf" class="btn btn-primary w-full text-center" formtarget="_blank" style="background: #3b82f6; border-color: #3b82f6;">PDF</button>
                    <button type="submit" name="format" value="csv" class="btn btn-primary w-full text-center" style="background: #10b981; border-color: #10b981;">CSV/Excel</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
