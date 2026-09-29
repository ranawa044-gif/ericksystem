@extends('layouts.app')

@section('title', 'Data Dosen')

@section('content')
<div class="mb-6 flex justify-between items-start flex-wrap gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="badge badge-primary" style="font-size: 0.75rem;">MANAJEMEN PENGELOLA</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">Data Dosen & Dosen Wali</h1>
        <p class="text-muted text-sm mt-1">Kelola data dosen, penugasan perwalian akademik, dan akun login sistem.</p>
    </div>
    <div>
        <a href="{{ route('pengelola.dosen.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 14px rgba(6, 182, 212, 0.3);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Tambah Dosen Baru</span>
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Stats Cards -->
@php
    $totalDosen = $dosens->count();
    $dosenWaliAktif = $dosens->filter(fn($d) => $d->mahasiswaBimbingan->count() > 0)->count();
    $totalBimbingan = $dosens->sum(fn($d) => $d->mahasiswaBimbingan->count());
@endphp
<div class="grid-3 mb-6">
    <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--primary);">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold text-muted uppercase tracking-wider">Total Dosen</div>
                <div class="text-3xl font-bold text-slate-800 mt-1">{{ $totalDosen }}</div>
                <div class="text-xs text-muted mt-1">Terdaftar di sistem E-RICK</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(6, 182, 212, 0.12); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 0; border-left: 4px solid var(--secondary);">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold text-muted uppercase tracking-wider">Dosen Wali Aktif</div>
                <div class="text-3xl font-bold text-slate-800 mt-1">{{ $dosenWaliAktif }}</div>
                <div class="text-xs text-muted mt-1">Memiliki mahasiswa bimbingan</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(20, 184, 166, 0.12); color: var(--secondary); display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 0; border-left: 4px solid #6366f1;">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold text-muted uppercase tracking-wider">Mahasiswa Terbimbing</div>
                <div class="text-3xl font-bold text-slate-800 mt-1">{{ $totalBimbingan }}</div>
                <div class="text-xs text-muted mt-1">Total mahasiswa perwalian</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99, 102, 241, 0.12); color: #6366f1; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Data Table Card -->
<div class="card">
    <div class="flex justify-between items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="font-bold text-lg text-slate-800 m-0">Daftar Dosen & Pembimbing Akademik</h2>
            <div class="text-xs text-muted">Menampilkan seluruh dosen beserta relasi akun login dan jumlah mahasiswa bimbingan.</div>
        </div>
        <div style="min-width: 250px;">
            <input type="text" id="searchInput" class="form-control text-sm" placeholder="Cari dosen, NIDN, atau jabatan..." onkeyup="filterDosenTable()">
        </div>
    </div>

    @if ($dosens->count() > 0)
        <div class="table-responsive">
            <table class="table" id="dosenTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Dosen</th>
                        <th>NIDN</th>
                        <th>Jabatan</th>
                        <th>Akun Login (Email)</th>
                        <th style="text-align: center;">Mahasiswa Bimbingan</th>
                        <th style="text-align: right; width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dosens as $index => $dosen)
                        @php
                            $mhsCount = $dosen->mahasiswaBimbingan->count();
                            $initials = collect(explode(' ', trim($dosen->nama)))
                                ->filter(fn($part) => !in_array(strtoupper(rtrim($part, '.,')), ['DR', 'DRA', 'DRS', 'IR', 'H', 'HJ', 'PROF', 'S.KOM', 'M.KOM', 'S.PD', 'M.PD', 'M.PD.T', 'S.T', 'M.AK', 'M.M', 'M.TI', 'M.SC', 'S.E']))
                                ->take(2)
                                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                ->implode('');
                            if (empty($initials)) {
                                $initials = strtoupper(substr($dosen->nama, 0, 2));
                            }
                        @endphp
                        <tr class="dosen-row">
                            <td class="text-muted text-sm font-semibold">{{ $index + 1 }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; box-shadow: 0 2px 6px rgba(6, 182, 212, 0.25);">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm dosen-name">{{ $dosen->nama }}</div>
                                        <div class="text-xs text-muted">ID: #{{ $dosen->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-mono text-xs font-semibold text-slate-700 dosen-nidn" style="background: #f1f5f9; padding: 2px 8px; border-radius: 6px;">
                                    {{ $dosen->nidn }}
                                </span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-700 dosen-jabatan">{{ $dosen->jabatan }}</span>
                            </td>
                            <td>
                                @if($dosen->user?->email)
                                    <div class="flex items-center gap-1 text-sm text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        <span class="font-mono text-xs">{{ $dosen->user->email }}</span>
                                    </div>
                                @else
                                    <span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 0.7rem;">Belum ada akun</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($mhsCount > 0)
                                    <a href="{{ route('pengelola.rekap.mahasiswa') }}?dosen_id={{ $dosen->id }}" class="badge badge-primary" style="font-size: 0.8rem; padding: 4px 10px; text-decoration: none; border-radius: 999px;">
                                        {{ $mhsCount }} Mahasiswa
                                    </a>
                                @else
                                    <span class="badge badge-secondary" style="font-size: 0.75rem; padding: 3px 8px; border-radius: 999px; background: #f1f5f9; color: #64748b;">
                                        0 Mahasiswa
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('pengelola.dosen.edit', $dosen) }}" class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;" title="Edit Dosen">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        <span>Edit</span>
                                    </a>

                                    @if ($mhsCount === 0)
                                        <form method="POST" action="{{ route('pengelola.dosen.destroy', $dosen) }}" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen {{ $dosen->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.8rem; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;" title="Hapus Dosen">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12" style="padding: 3rem 1rem;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(6, 182, 212, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
            </div>
            <h3 class="font-bold text-slate-800 text-base mb-1">Belum Ada Data Dosen</h3>
            <p class="text-sm text-muted mb-4">Tambahkan dosen pertama untuk mulai mengelola pembimbing akademik mahasiswa.</p>
            <a href="{{ route('pengelola.dosen.create') }}" class="btn btn-primary">Tambah Dosen Sekarang</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function filterDosenTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.dosen-row');

        rows.forEach(row => {
            const name = row.querySelector('.dosen-name')?.textContent.toLowerCase() || '';
            const nidn = row.querySelector('.dosen-nidn')?.textContent.toLowerCase() || '';
            const jabatan = row.querySelector('.dosen-jabatan')?.textContent.toLowerCase() || '';

            if (name.includes(query) || nidn.includes(query) || jabatan.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
