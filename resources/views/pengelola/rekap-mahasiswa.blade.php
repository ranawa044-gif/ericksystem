@extends('layouts.app')
@section('title', 'Rekapan Mahasiswa')

@push('styles')
<style>
    .metric-card {
        border-left: 5px solid var(--primary);
        height: 100%;
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary);
        line-height: 1;
    }

    .table th {
        white-space: nowrap;
    }

    .nilai-row {
        display: grid;
        grid-template-columns: minmax(200px, 1fr) 80px 80px;
        gap: 12px;
        align-items: center;
        padding: 4px 0;
    }

    .rank-badge {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: var(--primary-light, #e0f2fe);
        color: var(--primary);
        font-weight: 700;
    }
</style>
@endpush

@section('content')

<div class="flex justify-between items-start flex-wrap gap-4 mb-4">
    <div>
        <h1 class="text-2xl font-semibold mb-2">Rekapan Mahasiswa per Dosen Wali</h1>
        <p class="text-muted">Pantau peringkat, IPS, IPK, status semester, dan nilai mahasiswa berdasarkan dosen wali.</p>
    </div>
    
    <div class="card" style="padding: 1rem; margin-bottom: 0;">
        <div class="flex items-end gap-4">
            <form method="GET" action="{{ route('pengelola.rekap.mahasiswa') }}" class="flex-1 flex gap-4" id="filterForm">
                <div class="form-group mb-0 flex-1">
                    <label class="form-label text-xs">Filter Semester</label>
                    <select class="form-select" name="semester_id" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" {{ $semesterId === 'all' ? 'selected' : '' }}>Semua Semester (Kumulatif)</option>
                        @foreach($semesters as $s)
                            <option value="{{ $s->id }}" {{ (int) $semesterId === $s->id ? 'selected' : '' }}>
                                {{ $s->nama }} - {{ $s->tahun_akademik }} ({{ ucfirst($s->periode) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->role === 'admin')
                <div class="form-group mb-0 flex-1">
                    <label class="form-label text-xs">Filter Dosen Wali</label>
                    <select class="form-select" name="dosen_id" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" {{ $dosenId === 'all' ? 'selected' : '' }}>Semua Dosen Wali</option>
                        @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ (int) $dosenId === $d->id ? 'selected' : '' }}>
                                {{ $d->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
            <div class="form-group mb-0">
                <label class="form-label text-xs" style="visibility: hidden;">Cetak</label>
                <a href="{{ route('pengelola.rekap.mahasiswa.print', ['semester_id' => $semesterId, 'dosen_id' => $dosenId ?? 'all']) }}" target="_blank" class="btn btn-primary" style="white-space: nowrap; height: 38px; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

@php
    $totalDosen = $grouped->count();
    $totalMahasiswa = $grouped->flatten(1)->count();
    $selectedSemester = $semesterId === 'all'
        ? null
        : $semesters->firstWhere('id', (int) $semesterId);
@endphp

<div class="grid-3 mb-4">
    <div class="card metric-card">
        <p class="text-sm text-muted mb-2">Dosen Wali</p>
        <div class="metric-value">{{ $totalDosen }}</div>
    </div>
    <div class="card metric-card">
        <p class="text-sm text-muted mb-2">Mahasiswa Direkap</p>
        <div class="metric-value">{{ $totalMahasiswa }}</div>
    </div>
    <div class="card metric-card">
        <p class="text-sm text-muted mb-2">Semester Aktif</p>
        <div class="font-semibold text-lg text-primary">
            {{ $semesterId === 'all' ? 'Semua Semester' : (($selectedSemester?->nama ?? '-') . ' ' . ($selectedSemester?->tahun_akademik ?? '')) }}
        </div>
    </div>
</div>

@forelse($grouped as $mahasiswas)
    @php
        $dosenWali = optional($mahasiswas->first()->dosenWali)->nama ?? 'Tanpa Dosen Wali';
    @endphp
    
    <style>
        .freeze-col-1 { position: sticky; left: 0; z-index: 20; background: #fff; box-shadow: inset -1px 0 0 rgba(0,0,0,0.05); width: 80px; min-width: 80px; }
        .freeze-col-2 { position: sticky; left: 80px; z-index: 20; background: #fff; box-shadow: inset -1px 0 0 rgba(0,0,0,0.05); width: 250px; min-width: 250px; }
        .freeze-col-3 { position: sticky; left: 330px; z-index: 20; background: #fff; box-shadow: inset -1px 0 0 rgba(0,0,0,0.05); width: 80px; min-width: 80px; }
        .freeze-col-4 { position: sticky; left: 410px; z-index: 20; background: #fff; box-shadow: inset -1px 0 0 rgba(0,0,0,0.05); width: 80px; min-width: 80px; }
        .freeze-col-5 { position: sticky; left: 490px; z-index: 20; background: #fff; box-shadow: inset -1px 0 0 rgba(0,0,0,0.05); width: 150px; min-width: 150px; }

        thead th[class*="freeze-col-"] {
            z-index: 40; /* Header stays above scrolled rows */
            background: #f8fafc;
        }
    </style>

    <div class="card mb-4" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; min-width: 0; width: 100%;">
        <div class="flex justify-between items-start flex-wrap gap-4 p-4 border-b">
            <div>
                <h2 class="font-semibold text-lg text-primary">{{ $dosenWali }}</h2>
                <p class="text-sm text-muted">{{ $mahasiswas->count() }} mahasiswa dalam rekap ini.</p>
            </div>
            <span class="badge badge-primary">
                Urut {{ $semesterId === 'all' ? 'IPK' : 'IPS' }} tertinggi
            </span>
        </div>

        <div class="table-responsive" style="max-height: calc(100vh - 340px); overflow-y: auto; overflow-x: auto; width: 100%; max-width: 100%; display: block;">
            <table class="table align-middle" style="margin: 0; width: max-content; min-width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead style="position: sticky; top: 0; z-index: 30; background: #f8fafc; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <tr>
                        <th class="freeze-col-1">Peringkat</th>
                        <th class="freeze-col-2">NIM & Nama</th>
                        @if($semesterId !== 'all')
                            <th class="freeze-col-3">IPS</th>
                            <th class="freeze-col-4">IPK</th>
                            <th class="freeze-col-5">Status Semester</th>
                        @else
                            <th class="freeze-col-3">IPK</th>
                        @endif
                        <th style="background: #f8fafc; padding-right: 16px;">Detail Mata Kuliah & Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswas as $m)
                        @php
                            $krsData = $semesterId === 'all'
                                ? $m->krs->filter(fn($k) => $k->nilai)
                                : $m->krs->filter(fn($k) => $k->semester_id == $semesterId && $k->nilai);

                            $statusClass = match ($m->status_semester) {
                                'Tetap' => 'badge-success',
                                'Percobaan' => 'badge-warning',
                                'Tidak Lulus' => 'badge-danger',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <tr>
                            <td class="text-center freeze-col-1">
                                <span class="rank-badge">{{ $m->rank ?? '-' }}</span>
                            </td>
                            <td class="freeze-col-2">
                                <div class="font-semibold text-ink">{{ $m->nim }}</div>
                                <div class="text-sm text-muted">{{ $m->nama }}</div>
                            </td>
                            @if($semesterId !== 'all')
                                <td class="font-bold text-primary freeze-col-3">{{ number_format($m->ips, 2) }}</td>
                                <td class="font-bold freeze-col-4">{{ number_format($m->ipk, 2) }}</td>
                                <td class="freeze-col-5"><span class="badge {{ $statusClass }}">{{ $m->status_semester ?? '-' }}</span></td>
                            @else
                                <td class="font-bold freeze-col-3">{{ number_format($m->ipk, 2) }}</td>
                            @endif
                            <td style="white-space: nowrap;">
                                @if($krsData->isEmpty())
                                    <span class="text-muted text-sm italic">Belum ada nilai</span>
                                @else
                                    <div style="display: flex; gap: 8px; flex-wrap: nowrap; padding: 4px 0;">
                                        @foreach($krsData as $k)
                                            <div style="flex: 0 0 auto; width: max-content; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 10px; display: flex; align-items: center; gap: 12px;">
                                                <div style="font-size: 13px; font-weight: 500; color: var(--ink);">
                                                    {{ optional($k->mataKuliah)->nama ?? 'Tidak Ditemukan' }} 
                                                    <span class="text-muted" style="font-size: 11px;">({{ optional($k->mataKuliah)->sks ?? '-' }} SKS)</span>
                                                </div>
                                                <div style="display: flex; align-items: center; gap: 6px; border-left: 1px solid #e2e8f0; padding-left: 12px;">
                                                    <span style="font-size: 14px; font-weight: 700; color: var(--primary);">{{ optional($k->nilai)->nilai_angka ?? '-' }}</span>
                                                    <span class="badge badge-primary" style="font-size: 11px; padding: 2px 4px;">{{ optional($k->nilai)->nilai_huruf ?? '-' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="alert alert-info">
        Belum ada data mahasiswa untuk semester yang dipilih.
    </div>
@endforelse

@endsection
