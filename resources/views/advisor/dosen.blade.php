@extends('layouts.app')

@section('title', 'Smart Academic Advisor')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold mb-2 text-gray-800">Smart Academic Advisor (SPK)</h1>
        <p class="text-muted">Sistem Pendukung Keputusan penentuan prioritas pendampingan mahasiswa (Metode TOPSIS).</p>
    </div>
    <form action="{{ route('advisor.dosen.run') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-1"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3"/></svg>
            Jalankan Analisis Terbaru
        </button>
    </form>
</div>

@if(session('success'))
<div class="p-4 mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg">
    {{ session('success') }}
</div>
@endif

<div class="grid" style="grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="border-top: 4px solid #10b981;">
        <h3 class="text-sm font-semibold text-muted uppercase">Aman</h3>
        <p class="text-3xl font-bold mt-2 text-green-600">{{ $amanCount }} <span class="text-sm font-normal text-muted">Mahasiswa</span></p>
    </div>
    <div class="card" style="border-top: 4px solid #f59e0b;">
        <h3 class="text-sm font-semibold text-muted uppercase">Perlu Monitoring</h3>
        <p class="text-3xl font-bold mt-2 text-yellow-600">{{ $monitoringCount }} <span class="text-sm font-normal text-muted">Mahasiswa</span></p>
    </div>
    <div class="card" style="border-top: 4px solid #ef4444;">
        <h3 class="text-sm font-semibold text-muted uppercase">Perlu Pendampingan</h3>
        <p class="text-3xl font-bold mt-2 text-red-600">{{ $pendampinganCount }} <span class="text-sm font-normal text-muted">Mahasiswa</span></p>
    </div>
</div>

<div class="card mt-6">
    <h3 class="font-semibold text-lg mb-4">Ranking Prioritas Pendampingan</h3>
    
    @if($predictions->isEmpty())
        <div class="text-center py-8 text-muted">
            Belum ada data analisis. Silakan jalankan analisis terbaru.
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="table-auto w-full text-left" style="border-collapse: collapse;">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-3 text-sm font-medium text-slate-600">Rank</th>
                        <th class="p-3 text-sm font-medium text-slate-600">Mahasiswa</th>
                        <th class="p-3 text-sm font-medium text-slate-600">Skor SPK</th>
                        <th class="p-3 text-sm font-medium text-slate-600">Kategori</th>
                        <th class="p-3 text-sm font-medium text-slate-600">Rekomendasi & Detail</th>
                        <th class="p-3 text-sm font-medium text-slate-600">Proyek Akhir (PAMASESA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($predictions as $index => $pred)
                        @php
                            $details = is_array($pred->details) ? $pred->details : json_decode($pred->details, true);
                            $pa = isset($paSummaries) ? $paSummaries->get($pred->mahasiswa->nim) : null;
                        @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="p-3 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-3">
                                <a href="{{ route('dashboard.mahasiswa', $pred->mahasiswa->nim) }}" class="font-medium text-indigo-700 hover:text-indigo-900 hover:underline flex items-center gap-1">
                                    <span>{{ $pred->mahasiswa->nama }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </a>
                                <div class="text-xs text-slate-500">{{ $pred->mahasiswa->nim }}</div>
                            </td>
                            <td class="p-3 font-mono text-sm">{{ number_format($pred->score, 4) }}</td>
                            <td class="p-3">
                                @if($pred->category == 'Hijau')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Aman</span>
                                @elseif($pred->category == 'Kuning')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">Monitoring</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">Pendampingan</span>
                                @endif
                            </td>
                            <td class="p-3 text-sm">
                                <div>{{ $pred->recommendation }}</div>
                                @if($details)
                                <div class="mt-2 text-xs text-slate-500 bg-slate-100 p-2 rounded">
                                    <strong>Faktor:</strong> 
                                    IPK: {{ number_format($details['C1'] ?? 0, 2) }} | 
                                    Tren IPS: {{ number_format($details['C2'] ?? 0, 2) }} | 
                                    Nilai Bermasalah (D/E): {{ $details['C3'] ?? 0 }} | 
                                    Konsultasi: {{ $details['C4'] ?? 0 }} | 
                                    Prestasi: {{ $details['C5'] ?? 0 }}
                                </div>
                                @endif
                            </td>
                            <td class="p-3 text-sm">
                                @if($pa && $pa['has_pa'])
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $pa['badge_class'] }}">
                                            ● {{ $pa['status_label'] }}
                                        </span>
                                        @if(!empty($pa['title']))
                                            <div class="text-xs text-slate-700 font-medium mt-1 truncate max-w-xs" title="{{ $pa['title'] }}">
                                                "{{ Str::limit($pa['title'], 38) }}"
                                            </div>
                                        @endif
                                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-2 flex-wrap">
                                            @if(!empty($pa['pembimbing_1']))
                                                <span>P1: <strong>{{ Str::limit($pa['pembimbing_1'], 18) }}</strong></span>
                                            @endif
                                            <span class="bg-slate-100 px-1.5 py-0.5 rounded">Log: {{ $pa['verified_logs'] }}/{{ $pa['total_logs'] }}</span>
                                        </div>
                                    </div>
                                @elseif($pa && $pa['status'] === 'NOT_SUBMITTED')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Belum Ajukan Judul
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs text-slate-500 bg-slate-100">
                                        {{ $pa['status_label'] ?? 'Belum Terdaftar' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
