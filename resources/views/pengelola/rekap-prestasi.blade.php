@extends('layouts.app')
@section('title', 'Rekap Prestasi Mahasiswa')

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

    .proof-thumb {
        width: 88px;
        height: 64px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
    }
</style>
@endpush

@section('content')

<div class="flex justify-between items-start flex-wrap gap-4 mb-4">
    <div>
        <h1 class="text-2xl font-semibold mb-2">Rekap Prestasi Mahasiswa</h1>
        <p class="text-muted">
            @if (auth()->user()->role === 'admin')
                Lihat seluruh prestasi mahasiswa dan filter berdasarkan dosen wali.
            @else
                Lihat prestasi mahasiswa yang berada di bawah bimbingan wali akademik Anda.
            @endif
        </p>
    </div>
    
    <div class="card" style="min-width: 300px;">
        @if (auth()->user()->role === 'admin')
            <form method="GET">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="dosen_id">Filter Dosen Wali</label>
                    <select class="form-select" name="dosen_id" id="dosen_id" onchange="this.form.submit()">
                        <option value="">Semua Dosen Wali</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected((string) $dosenId === (string) $dosen->id)>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        @else
            <div>
                <span class="text-sm text-muted block mb-1">Dosen Wali</span>
                <strong class="text-lg">{{ auth()->user()->dosen?->nama ?? '-' }}</strong>
            </div>
        @endif
    </div>
</div>

@php
    $totalMahasiswa = $prestasi->pluck('mahasiswa_id')->unique()->count();
    $totalBukti = $prestasi->filter(fn ($item) => $item->bukti_file)->count();
    $selectedDosen = $dosenId ? $dosens->firstWhere('id', (int) $dosenId) : null;
@endphp

<div class="grid-3 mb-4">
    <div class="card metric-card">
        <p class="text-sm text-muted mb-2">Total Prestasi</p>
        <div class="metric-value">{{ $prestasi->count() }}</div>
    </div>
    <div class="card metric-card">
        <p class="text-sm text-muted mb-2">Mahasiswa Aktif</p>
        <div class="metric-value">{{ $totalMahasiswa }}</div>
    </div>
    <div class="card metric-card">
        <p class="text-sm text-muted mb-2">Bukti Terunggah</p>
        <div class="metric-value">{{ $totalBukti }}</div>
    </div>
</div>

@if ($selectedDosen)
    <div class="alert alert-primary mb-4">
        Menampilkan prestasi mahasiswa wali: <strong>{{ $selectedDosen->nama }}</strong>
    </div>
@endif

@forelse ($grouped as $items)
    @php
        $dosenWali = $items->first()->mahasiswa->dosenWali?->nama ?? 'Tanpa Dosen Wali';
    @endphp

    <div class="card mb-4">
        <div class="flex justify-between items-start flex-wrap gap-4 mb-4">
            <div>
                <h2 class="font-semibold text-lg">{{ $dosenWali }}</h2>
                <p class="text-sm text-muted">{{ $items->count() }} prestasi/kegiatan tercatat.</p>
            </div>
            <span class="badge badge-primary">{{ $items->pluck('mahasiswa_id')->unique()->count() }} mahasiswa</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Kegiatan</th>
                        <th>Jenis</th>
                        <th>Tempat</th>
                        <th>Tanggal</th>
                        <th>Pembimbing</th>
                        <th>Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        @php
                            $extension = strtolower(pathinfo($item->bukti_file ?? '', PATHINFO_EXTENSION));
                            $buktiUrl = $item->bukti_file ? asset('uploads/prestasi/' . $item->bukti_file) : null;
                        @endphp
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $item->mahasiswa->nim }}</div>
                                <div class="text-sm text-muted">{{ $item->mahasiswa->nama }}</div>
                            </td>
                            <td>
                                <div class="font-semibold">{{ $item->nama_kegiatan }}</div>
                                @if ($item->keterangan)
                                    <div class="text-sm text-muted mt-1">{{ $item->keterangan }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($item->jenis_kegiatan)
                                    <span class="badge badge-primary">{{ $item->jenis_kegiatan }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->tempat ?: '-' }}</td>
                            <td>{{ $item->tanggal?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $item->pembimbing ?: '-' }}</td>
                            <td>
                                @if ($buktiUrl)
                                    @if (in_array($extension, ['jpg', 'jpeg', 'png'], true))
                                        <a href="{{ $buktiUrl }}" target="_blank" rel="noopener">
                                            <img src="{{ $buktiUrl }}" alt="Bukti {{ $item->nama_kegiatan }}" class="proof-thumb">
                                        </a>
                                    @else
                                        <a href="{{ $buktiUrl }}" target="_blank" rel="noopener" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Lihat PDF</a>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
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
        Belum ada data prestasi mahasiswa untuk filter ini.
    </div>
@endforelse

@endsection
