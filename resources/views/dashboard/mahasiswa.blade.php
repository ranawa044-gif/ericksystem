@extends('layouts.app')
@section('title', 'Dashboard Akademik')

@push('styles')
<style>
    /* Dashboard helper styles */
    .grid-1, .grid-2, .grid-3, .grid-4 {
        display: grid;
        gap: 1.25rem;
    }
    .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .card {
        border-radius: var(--radius-md);
        background: white;
        border: 1px solid var(--line);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-primary { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
    .badge-secondary { background: rgba(148, 163, 184, 0.16); color: #475569; }
    .badge-success { background: rgba(16, 185, 129, 0.12); color: #047857; }
    .badge-danger { background: rgba(239, 68, 68, 0.12); color: #b91c1c; }
    .badge-warning { background: rgba(245, 158, 11, 0.12); color: #b45309; }

    .accordion-item {
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        margin-bottom: 1rem;
        overflow: hidden;
        background: white;
    }
    .accordion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        cursor: pointer;
        background: var(--bg-page);
        transition: background 0.2s;
    }
    .accordion-header:hover { background: #f1f5f9; }
    .accordion-title { font-weight: 600; font-size: 1rem; }
    .accordion-badge { font-weight: 700; font-size: 1.25rem; color: var(--primary); }
    .accordion-content {
        display: none;
        padding: 0;
        border-top: 1px solid var(--line);
    }
    .accordion-content.active { display: block; }
    .accordion-icon {
        transition: transform 0.3s;
    }
    .accordion-header.active .accordion-icon {
        transform: rotate(180deg);
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .status-aman { background: var(--success); box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); }
    .status-perhatian { background: var(--warning); box-shadow: 0 0 8px rgba(245, 158, 11, 0.4); }
    .status-kritis { background: var(--danger); box-shadow: 0 0 8px rgba(239, 68, 68, 0.4); }

    .stat-card-highlight {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.16), rgba(56, 189, 248, 0.1));
        border: 1px solid rgba(6, 182, 212, 0.3);
        box-shadow: 0 12px 30px rgba(6, 182, 212, 0.08);
    }
    .stat-card-highlight .text-muted,
    .stat-card-highlight .text-2xl {
        color: #0f172a;
    }

    .buddy-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.08), rgba(15, 23, 42, 0.03));
        border: 1px solid rgba(6, 182, 212, 0.18);
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
    }
    .buddy-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: white;
        font-weight: 700;
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.25);
    }
    @media (max-width: 768px) {
        .buddy-card {
            max-width: 100% !important;
        }
    }
    .buddy-profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        border: 4px solid white;
        background: white;
    }
    .buddy-avatar-initial {
        width: 100%;
        height: 100%;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #06b6d4, #0f172a);
        color: white;
        font-size: 2rem;
        font-weight: 700;
    }
    .buddy-details {
        border: 1px solid var(--line);
    }
    .buddy-message {
        background: rgba(6, 182, 212, 0.08);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .buddy-footer {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .buddy-progress-card .progress-track {
        background: #e2e8f0;
    }

    .timeline {
        display: grid;
        gap: 1rem;
    }
    .timeline-item {
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        padding: 1rem;
        background: white;
    }
    .timeline-item.completed { border-color: rgba(16, 185, 129, 0.35); }
    .timeline-item.at-risk { border-color: rgba(245, 158, 11, 0.35); }
    .timeline-item.not-started { border-color: rgba(248, 113, 113, 0.25); }
    .timeline-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--muted);
        font-size: 0.9rem;
    }

    .mission-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.75rem;
    }
    .mission-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--line);
        background: white;
        color: var(--ink);
    }
    .mission-item.completed {
        background: rgba(16, 185, 129, 0.09);
        border-color: rgba(16, 185, 129, 0.25);
    }
    .mission-marker {
        width: 20px;
        height: 20px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: rgba(6, 182, 212, 0.12);
        color: var(--primary);
        font-weight: 700;
    }
    .mini-prestasi-card {
        padding: 1rem;
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
    }

    .text-lg { font-size: 1.05rem; }
    .text-sm { font-size: 0.9rem; }
    .text-muted { color: #6b7280; }

    @media (max-width: 1200px) {
        .grid-4,
        .grid-3,
        .grid-2,
        .grid-1 {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 768px) {
        .buddy-footer {
            flex-direction: column;
            align-items: flex-start;
        }
        .buddy-profile-photo {
            width: 100px;
            height: 100px;
        }
        .text-3xl { font-size: 2rem; }
        .text-lg { font-size: 1rem; }
        .text-sm { font-size: 0.85rem; }
        .timeline-item,
        .mission-item,
        .mini-prestasi-card {
            padding: 0.85rem;
        }
    }
    @media (max-width: 480px) {
        .card,
        .buddy-card,
        .buddy-summary,
        .buddy-progress-card,
        .buddy-quote-card {
            padding: 1rem;
        }
        .buddy-footer {
            gap: 0.5rem;
        }
        .badge { font-size: 0.75rem; }
    }
</style>
@endpush

@section('content')

@php
    // Determine status based on IPK
    $statusClass = 'status-aman';
    $statusText = 'Aman';
    if ($ipk < 2.0) {
        $statusClass = 'status-kritis';
        $statusText = 'Butuh Konsultasi (Kritis)';
    } elseif ($ipk < 2.75) {
        $statusClass = 'status-perhatian';
        $statusText = 'Perlu Perhatian';
    }

    $greetingHour = (int) now()->format('H');
    if ($greetingHour < 10) {
        $greeting = 'Selamat Pagi';
    } elseif ($greetingHour < 16) {
        $greeting = 'Selamat Siang';
    } else {
        $greeting = 'Selamat Sore';
    }

    $buddyMood = $academicHealth['label'] === 'At Risk' ? 'Waspada' : ($academicHealth['label'] === 'Warning' ? 'Semangat!' : 'Hebat!');
@endphp

<div class="mb-4">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div style="flex: 1 1 300px;">
            <h1 class="text-3xl font-semibold mb-2">{{ $greeting }}, {{ explode(' ', trim($mahasiswa->nama))[0] }}.</h1>
            <p class="text-muted">Ini adalah teman akademikmu, E-RICK Buddy. Ayo kita lihat perkembangan dan misi hari ini.</p>
        </div>
        <div class="card buddy-card" style="width: 100%; max-width: 400px; flex: 1 1 280px;">
            <div class="flex items-center gap-3 mb-3">
                <div class="buddy-avatar">ER</div>
                <div>
                    <div class="text-sm text-muted">E-RICK Buddy</div>
                    <div class="font-semibold">Halo, {{ explode(' ', trim($mahasiswa->nama))[0] }}!</div>
                </div>
            </div>
            <div class="buddy-message">
                <p>Saat ini kondisi akademikmu <strong>{{ $academicHealth['label'] }}</strong>. {{ $academicHealth['description'] }}</p>
            </div>
            <div class="buddy-footer">
                <span class="badge badge-primary">Mood: {{ $buddyMood }}</span>
                <span class="badge badge-secondary"> {{ $currentSemesterName }}</span>
            </div>
        </div>
    </div>
</div>

<div class="grid-3 mb-4">
    <!-- Profile Card -->
    <div class="card buddy-summary" style="grid-column: span 1; display: flex; flex-direction: column; align-items: center; text-align: center;">
        <div class="buddy-profile-photo" style="margin-bottom: 1rem;">
            @if ($mahasiswa->foto)
                <img src="{{ asset('uploads/' . $mahasiswa->foto) }}" alt="{{ $mahasiswa->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div class="buddy-avatar-initial">{{ substr($mahasiswa->nama, 0, 1) }}</div>
            @endif
        </div>
        <h2 class="font-semibold text-xl mb-1">{{ $mahasiswa->nama }}</h2>
        <p class="text-muted mb-4">{{ $mahasiswa->nim }}</p>
        
        <div class="buddy-details" style="width: 100%; text-align: left; background: var(--bg-page); padding: 1rem; border-radius: var(--radius-md);">
            <div class="flex justify-between mb-2">
                <span class="text-muted text-sm">Program Studi</span>
                <span class="font-semibold text-sm">{{ $mahasiswa->program_studi }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-muted text-sm">Angkatan</span>
                <span class="font-semibold text-sm">{{ $mahasiswa->angkatan }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-muted text-sm">Dosen Wali</span>
                <span class="font-semibold text-sm">{{ $mahasiswa->dosenWali?->nama ?? 'Belum ditetapkan' }}</span>
            </div>
            <div class="flex justify-between mt-3 pt-3" style="border-top: 1px solid var(--line);">
                <span class="text-muted text-sm">Status Akademik</span>
                <span class="font-semibold text-sm flex items-center">
                    <span class="status-indicator {{ $statusClass }}"></span> {{ $statusText }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats & Chart -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="grid-4">
            <div class="card stat-card-highlight" style="margin-bottom: 0;">
                <div class="text-muted text-sm font-semibold uppercase mb-2">IPS Terakhir</div>
                <div class="text-2xl font-semibold text-primary">{{ number_format($ips, 2) }}</div>
            </div>
            <div class="card stat-card-highlight" style="margin-bottom: 0;">
                <div class="text-muted text-sm font-semibold uppercase mb-2">IPK Kumulatif</div>
                <div class="text-2xl font-semibold text-primary">{{ number_format($ipk, 2) }}</div>
            </div>
            <div class="card" style="margin-bottom: 0;">
                <div class="text-muted text-sm font-semibold uppercase mb-2">Total SKS</div>
                <div class="text-2xl font-semibold">{{ $totalSks }}</div>
            </div>
            <div class="card" style="margin-bottom: 0;">
                <div class="text-muted text-sm font-semibold uppercase mb-2">Total Prestasi</div>
                <div class="text-2xl font-semibold text-warning">{{ $totalPrestasi }}</div>
            </div>
        </div>

        <div class="card buddy-progress-card" style="margin-bottom: 0;">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <div class="text-muted text-sm uppercase font-semibold">Academic Energy</div>
                    <div class="text-lg font-semibold">{{ $academicEnergy }}%</div>
                </div>
                <span class="badge {{ $academicHealth['class'] }}">{{ $academicHealth['label'] }}</span>
            </div>
            <div class="progress-track" style="height: 14px; border-radius: 999px; overflow: hidden; background: #e2e8f0;">
                <div class="progress-fill" style="width: {{ $academicEnergy }}%; background: linear-gradient(90deg, #06b6d4, #14b8a6); height: 100%;"></div>
            </div>
            <p class="text-sm text-muted mt-3">{{ $academicHealth['description'] }}</p>
        </div>

        <div class="card buddy-quote-card" style="margin-bottom: 0;">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <div class="text-muted text-sm uppercase font-semibold">Motivasi Hari Ini</div>
                    <div class="font-semibold">E-RICK Buddy siap mendukungmu.</div>
                </div>
                <!-- reload icon removed -->
            </div>
            <p class="text-lg">"<span id="motivationalQuoteText">{{ $motivationalQuote }}</span>"</p>
        </div>

        @if(isset($prediction))
        <div class="card" style="background: linear-gradient(135deg, rgba(6,182,212,0.05) 0%, rgba(20,184,166,0.05) 100%); border-left: 4px solid var(--primary);">
            <div class="flex items-center gap-3 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                <h3 class="font-semibold text-lg m-0">Academic Insight</h3>
            </div>
            <p class="text-sm mb-1"><strong>Status Akademik:</strong> Baik</p>
            <p class="text-sm"><strong>Rekomendasi:</strong> {{ $prediction->recommendation }}</p>
        </div>
        @endif

        <div class="card" style="flex: 1; margin-bottom: 0; margin-top: -1.5rem;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold">Grafik Perkembangan IPK</h3>
            </div>
            @if (count($statistikIpk['values']) > 0)
                <div style="height: 250px; position: relative;">
                    <canvas id="ipkSemesterChart"></canvas>
                </div>
            @else
                <div class="alert alert-secondary">Belum ada data IPK untuk ditampilkan grafik.</div>
            @endif
        </div>
    </div>
</div>

<!-- Widget Status Proyek Akhir (PAMASESA) -->
<div class="card mb-4" style="background: #ffffff; border: 1px solid rgba(79, 70, 229, 0.2); box-shadow: 0 10px 25px rgba(79, 70, 229, 0.05); border-radius: 16px; overflow: hidden;">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(13, 148, 136, 0.08) 100%); padding: 1rem 1.25rem; border-bottom: 1px solid rgba(79, 70, 229, 0.12); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div class="flex items-center gap-3">
            <div style="width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, #4f46e5, #0d9488); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-lg text-slate-800 m-0">Proyek Akhir &middot; PAMASESA</h3>
                    <span style="font-size: 0.7rem; letter-spacing: 0.05em; font-weight: 700; padding: 2px 8px; border-radius: 9999px; background: rgba(79, 70, 229, 0.12); color: #4338ca;">LIVE TERINTEGRASI</span>
                </div>
                <div class="text-xs text-muted">Manajemen Seminar dan Sidang Akhir Terpadu D3 Sistem Informasi</div>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ $paSummary['app_url'] ?? 'http://pamasesa2.test' }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background: white; border: 1px solid rgba(79, 70, 229, 0.3); color: #4338ca; font-size: 0.8rem; padding: 0.35rem 0.75rem; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; font-weight: 600;">
                <span>Portal PAMASESA (Lokal)</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            </a>
            @if(!empty($paSummary['online_url']))
            <a href="{{ $paSummary['online_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background: linear-gradient(135deg, #4f46e5, #0d9488); border: none; color: white; font-size: 0.8rem; padding: 0.35rem 0.75rem; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                <span>Online Cloudflare</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Body -->
    <div style="padding: 1.25rem;">
        @if(!$paSummary['is_connected'])
            <div class="alert alert-secondary mb-0 flex items-center gap-3" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#64748b" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <div class="text-sm text-slate-600">
                    {{ $paSummary['message'] }}
                </div>
            </div>
        @elseif(!$paSummary['has_pa'])
            <div style="background: #fdfaf6; border: 1px solid #fed7aa; border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="flex: 1 1 300px;">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background: {{ $paSummary['badge_bg'] }}; color: {{ $paSummary['badge_text'] }};">
                            {{ $paSummary['status_label'] }}
                        </span>
                        <span class="text-xs text-muted">Belum ada pengajuan terdata</span>
                    </div>
                    <h4 class="font-bold text-slate-800 text-base mb-1">Mahasiswa Belum Mengajukan Judul Proyek Akhir</h4>
                    <p class="text-sm text-slate-600 m-0">Segera persiapkan usulan judul, calon pembimbing, serta rancangan sistem, lalu ajukan melalui portal PAMASESA untuk memulai bimbingan Proyek Akhir.</p>
                </div>
                <div>
                    <a href="{{ $paSummary['app_url'] }}/student/final-projects/create" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="font-weight: 600; padding: 0.6rem 1.2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.25);">
                        Ajukan Judul di PAMASESA &rarr;
                    </a>
                </div>
            </div>
        @else
            <!-- PA Details -->
            <div>
                <div class="flex items-center justify-between flex-wrap gap-2 mb-2">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" style="background: {{ $paSummary['badge_bg'] }}; color: {{ $paSummary['badge_text'] }}; border: 1px solid rgba(0,0,0,0.06);">
                            ● {{ $paSummary['status_label'] }}
                        </span>
                        <span class="text-xs text-muted">Skema / Tipe: <strong>{{ $paSummary['system_type'] }}</strong></span>
                    </div>
                    @if($paSummary['submitted_at'])
                        <div class="text-xs text-muted">Diajukan: {{ \Carbon\Carbon::parse($paSummary['submitted_at'])->format('d M Y H:i') }}</div>
                    @endif
                </div>

                <h4 class="font-bold text-slate-800 text-base mb-3 leading-relaxed" style="color: #1e293b;">
                    "{{ $paSummary['title'] }}"
                </h4>

                <!-- Grid Ringkasan PA -->
                <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                    <!-- Pembimbing 1 -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.85rem;">
                        <div class="text-xs text-muted uppercase font-semibold mb-1">Dosen Pembimbing 1</div>
                        <div class="font-bold text-sm text-slate-800">{{ $paSummary['pembimbing_1'] }}</div>
                        <div class="text-xs text-slate-500 mt-1">Status Persetujuan: <strong>{{ $paSummary['supervisor_one_approval'] }}</strong></div>
                    </div>

                    <!-- Pembimbing 2 -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.85rem;">
                        <div class="text-xs text-muted uppercase font-semibold mb-1">Dosen Pembimbing 2</div>
                        <div class="font-bold text-sm text-slate-800">{{ $paSummary['pembimbing_2'] }}</div>
                        <div class="text-xs text-slate-500 mt-1">Status Persetujuan: <strong>{{ $paSummary['supervisor_two_approval'] }}</strong></div>
                    </div>

                    <!-- Bimbingan Logbook -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.85rem;">
                        <div class="text-xs text-muted uppercase font-semibold mb-1">Log Bimbingan PA</div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-800">{{ $paSummary['progress']['verified_logs'] }} / {{ $paSummary['progress']['total_logs'] }} Diverifikasi</span>
                            <span class="text-xs {{ $paSummary['progress']['meets_seminar'] ? 'text-green-600 font-semibold' : 'text-amber-600' }}">
                                {{ $paSummary['progress']['meets_seminar'] ? '✓ Kuota Sempro' : 'Belum Kuota' }}
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2 mt-2 overflow-hidden" style="background: #e2e8f0; border-radius: 999px;">
                            @php
                                $pct = $paSummary['progress']['total_logs'] > 0 ? min(100, round(($paSummary['progress']['verified_logs'] / 10) * 100)) : 0;
                            @endphp
                            <div style="width: {{ $pct }}%; background: linear-gradient(90deg, #4f46e5, #0d9488); height: 100%;"></div>
                        </div>
                    </div>

                    <!-- Status Seminar & Sidang -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.85rem;">
                        <div class="text-xs text-muted uppercase font-semibold mb-1">Tahap Ujian Akhir</div>
                        <div class="text-xs text-slate-700 mb-1 flex items-center justify-between">
                            <span>Seminar Proposal:</span>
                            <span class="font-semibold">{{ $paSummary['seminar']['has_submitted'] ? $paSummary['seminar']['status'] : 'Belum Daftar' }}</span>
                        </div>
                        <div class="text-xs text-slate-700 flex items-center justify-between">
                            <span>Sidang Akhir:</span>
                            <span class="font-semibold">{{ $paSummary['defense']['has_submitted'] ? $paSummary['defense']['status'] : 'Belum Daftar' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="grid-3 mb-4">
    <!-- Semester Accordions -->
    <div style="grid-column: span 2;">
        <h3 class="font-semibold text-xl mb-4">Daftar Nilai Per Semester</h3>
        
        @if ($semesters->isEmpty())
            <div class="alert alert-secondary">Belum ada data semester.</div>
        @else
            @foreach($semesters->sortByDesc('urutan')->values() as $index => $semester)
                @php
                    $krsSemester = $mahasiswa->krs()->where('semester_id', $semester->id)->with('mataKuliah', 'nilai')->get();
                    $sksSemester = $krsSemester->sum(function($k) { return $k->mataKuliah->sks; });
                    $bobotTotal = $krsSemester->sum(function($k) { return ($k->nilai?->bobot ?? 0) * $k->mataKuliah->sks; });
                    $ipsSemester = $sksSemester > 0 ? $bobotTotal / $sksSemester : 0;
                @endphp
                <div class="accordion-item">
                    <div class="accordion-header {{ $index === 0 ? 'active' : '' }}" onclick="toggleAccordion(this)">
                        <div>
                            <div class="accordion-title">{{ $semester->nama }}</div>
                            <div class="text-sm text-muted">{{ $sksSemester }} SKS Total</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-sm text-muted">IPS</div>
                                <div class="accordion-badge">{{ number_format($ipsSemester, 2) }}</div>
                            </div>
                            <svg class="accordion-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div class="accordion-content {{ $index === 0 ? 'active' : '' }}">
                        <div class="table-responsive" style="border: none; border-radius: 0;">
                            <table class="table" style="margin: 0;">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Mata Kuliah</th>
                                        <th>SKS</th>
                                        <th>Nilai Angka</th>
                                        <th>Huruf Mutu</th>
                                        <th>Bobot</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($krsSemester as $krs)
                                        <tr>
                                            <td class="font-semibold">{{ $krs->mataKuliah->kode }}</td>
                                            <td>{{ $krs->mataKuliah->nama }}</td>
                                            <td>{{ $krs->mataKuliah->sks }}</td>
                                            <td class="font-semibold">{{ $krs->nilai ? number_format($krs->nilai->nilai_angka, 2) : '-' }}</td>
                                            <td>
                                                <span class="badge badge-primary">{{ $krs->nilai?->nilai_huruf ?? '-' }}</span>
                                            </td>
                                            <td class="font-semibold">{{ $krs->nilai ? number_format($krs->nilai->bobot, 2) : '-' }}</td>
                                            <td>
                                                @if ($krs->nilai?->status_lulus)
                                                    <span class="badge badge-success">Lulus</span>
                                                @else
                                                    <span class="badge badge-danger">Belum Lulus</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada mata kuliah yang diambil.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Catatan Dosen -->
    <div>
        <h3 class="font-semibold text-xl mb-4">Catatan Dosen Wali</h3>
        <div class="card">
            @forelse ($mahasiswa->catatanDosenWali()->latest()->take(5)->get() as $catatan)
                <div style="border-left: 3px solid var(--primary); padding-left: 1rem; margin-bottom: 1.5rem;">
                    <div class="text-sm text-muted mb-1">{{ $catatan->tanggal->format('d M Y') }}</div>
                    <p class="mb-2 text-sm">{{ $catatan->catatan }}</p>
                    <div class="text-xs font-semibold text-primary">- {{ $catatan->dosen->nama }}</div>
                </div>
            @empty
                <div class="text-muted text-sm">Belum ada catatan dari dosen wali.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    // Accordion Logic
    function toggleAccordion(element) {
        element.classList.toggle('active');
        const content = element.nextElementSibling;
        content.classList.toggle('active');
    }

    // Chart Logic
    const ipkLabels = @json($statistikIpk['labels']);
    const ipkValues = @json($statistikIpk['values']);
    const chartCanvas = document.getElementById('ipkSemesterChart');

    if (chartCanvas && ipkValues.length > 0) {
        const minIpk = Math.max(0, Math.floor((Math.min(...ipkValues) - 0.2) * 10) / 10);
        const maxIpk = Math.min(4, Math.ceil((Math.max(...ipkValues) + 0.2) * 10) / 10);

        new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: ipkLabels,
                datasets: [{
                    label: 'IPK',
                    data: ipkValues,
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#06b6d4',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        min: minIpk,
                        max: maxIpk,
                        ticks: { stepSize: 0.1 },
                        grid: { color: 'rgba(226, 232, 240, 0.5)' }
                    },
                    x: {
                        grid: { color: 'rgba(226, 232, 240, 0.5)' }
                    }
                }
            }
        });
    }
</script>
@endpush
