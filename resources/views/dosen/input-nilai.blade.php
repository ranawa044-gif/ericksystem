@extends('layouts.app')
@section('title', $editKrs ? 'Edit Nilai Mahasiswa' : 'Input Nilai Mahasiswa')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-semibold mb-2">{{ $editKrs ? 'Edit Nilai Mahasiswa' : 'Input Nilai Mahasiswa' }}</h1>
    <p class="text-muted">Admin menginput nilai berdasarkan NIM, semester, dan mata kuliah.</p>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (!$editKrs)
<div class="grid-2 mb-4">
    <div class="card" style="margin-bottom: 0;">
        <div class="flex items-center gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="var(--success)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            <h2 class="font-semibold text-lg">Import Nilai via Excel</h2>
        </div>
        <p class="text-sm text-muted mb-4">Cara tercepat menginput nilai kolektif. Unduh template, isi, lalu unggah file Excel Anda ke sini.</p>
        
        <form method="POST" action="{{ route('pengelola.import.nilai') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Upload Excel (.xlsx)</label>
                <input class="form-control border-dashed border-2" type="file" name="file" accept=".xlsx" required style="margin-bottom: 0.5rem; border-color: var(--success); padding: 1rem;">
                <a href="{{ route('pengelola.import.nilai.template') }}" class="text-sm font-semibold" style="color: var(--success); text-decoration: underline;">Unduh Template Excel</a>
            </div>
            <button class="btn w-full" style="background: var(--success); color: white;" type="submit">Mulai Import Nilai</button>
        </form>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <h2 class="font-semibold text-lg mb-4">Ketentuan Range Nilai</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Huruf</th>
                        <th>Range Nilai Angka</th>
                        <th>Bobot (SKS)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rangeNilai as $range)
                        <tr>
                            <td><span class="badge badge-primary" style="font-size: 0.875rem;">{{ $range['huruf'] }}</span></td>
                            <td class="text-muted">{{ number_format($range['min'], 2) }} - {{ number_format($range['max'], 2) }}</td>
                            <td class="font-semibold">{{ number_format($range['bobot'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card mb-4">
    <h2 class="font-semibold text-lg mb-4">Ketentuan Range Nilai</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Huruf</th>
                    <th>Range Nilai Angka</th>
                    <th>Bobot (SKS)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rangeNilai as $range)
                    <tr>
                        <td><span class="badge badge-primary" style="font-size: 0.875rem;">{{ $range['huruf'] }}</span></td>
                        <td class="text-muted">{{ number_format($range['min'], 2) }} - {{ number_format($range['max'], 2) }}</td>
                        <td class="font-semibold">{{ number_format($range['bobot'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card mb-4">
    <h2 class="font-semibold text-lg mb-4">{{ $editKrs ? 'Edit Nilai Mahasiswa' : 'Input Nilai Manual (Satu per Satu)' }}</h2>
    <form method="POST" action="{{ $editKrs ? route('pengelola.input-nilai.update', $editKrs) : route('pengelola.input-nilai.store') }}">
        @csrf
        @if ($editKrs)
            @method('PUT')
        @endif
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label" for="nim">NIM Mahasiswa</label>
                <input class="form-control" id="nim" name="nim" list="daftar-mahasiswa" value="{{ old('nim', $editKrs?->mahasiswa?->nim) }}" required>
                <datalist id="daftar-mahasiswa">
                    @foreach ($mahasiswas as $mahasiswa)
                        <option value="{{ $mahasiswa->nim }}">{{ $mahasiswa->nama }}</option>
                    @endforeach
                </datalist>
            </div>

            <div class="form-group">
                <label class="form-label" for="semester_id">Semester</label>
                <select class="form-select" id="semester_id" name="semester_id" required>
                    <option value="">Pilih semester</option>
                    @foreach ($semesters as $semester)
                        <option value="{{ $semester->id }}" @selected(old('semester_id', $editKrs?->semester_id) == $semester->id)>
                            {{ $semester->nama }} - {{ $semester->tahun_akademik }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label class="form-label" for="mata_kuliah_id">Mata Kuliah</label>
                <select class="form-select" id="mata_kuliah_id" name="mata_kuliah_id" required>
                    <option value="">Pilih mata kuliah</option>
                    @foreach ($mataKuliahs as $mataKuliah)
                        <option value="{{ $mataKuliah->id }}" @selected(old('mata_kuliah_id', $editKrs?->mata_kuliah_id) == $mataKuliah->id)>
                            {{ $mataKuliah->kode }} - {{ $mataKuliah->nama }} ({{ $mataKuliah->sks }} SKS)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="nilai_angka">Nilai Angka (0-100)</label>
                <input class="form-control" id="nilai_angka" type="number" name="nilai_angka" min="0" max="100" step="0.01" value="{{ old('nilai_angka', $editKrs?->nilai?->nilai_angka) }}" required>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button class="btn btn-primary" type="submit">{{ $editKrs ? 'Simpan Perubahan Nilai' : 'Simpan Nilai Manual' }}</button>
            @if ($editKrs)
                <a href="{{ route('pengelola.input-nilai') }}" class="btn btn-secondary">Batal Edit</a>
            @endif
        </div>
    </form>
</div>

<div class="card">
    <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
        <div>
            <h2 class="font-semibold text-lg">Riwayat Nilai Terinput</h2>
            <p class="text-sm text-muted">Menampilkan {{ $nilaiList->firstItem() ?? 0 }}-{{ $nilaiList->lastItem() ?? 0 }} dari {{ $nilaiList->total() }} data.</p>
        </div>

        <form class="flex gap-2 items-end flex-wrap" method="GET" action="{{ route('pengelola.input-nilai') }}">
            <div class="form-group" style="margin-bottom: 0;">
                <input class="form-control" id="q" name="q" value="{{ $search }}" placeholder="Cari NIM, nama, kode MK">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                    <option value="10" @selected($perPage === 10)>10 data</option>
                    <option value="25" @selected($perPage === 25)>25 data</option>
                    <option value="50" @selected($perPage === 50)>50 data</option>
                </select>
            </div>
            <button class="btn btn-secondary" type="submit">Filter</button>
            @if ($search !== '')
                <a href="{{ route('pengelola.input-nilai') }}" class="btn btn-danger">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Mahasiswa</th>
                    <th>Semester</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Nilai (A/H/B)</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nilaiList as $krs)
                    <tr>
                        <td class="font-semibold">{{ $krs->mahasiswa->nim }}</td>
                        <td>{{ $krs->mahasiswa->nama }}</td>
                        <td>{{ $krs->semester->nama }}</td>
                        <td>{{ $krs->mataKuliah->kode }} - {{ $krs->mataKuliah->nama }}</td>
                        <td>{{ $krs->mataKuliah->sks }}</td>
                        <td>
                            <span class="font-semibold">{{ number_format($krs->nilai->nilai_angka, 2) }}</span> / 
                            <span class="text-primary font-semibold">{{ $krs->nilai->nilai_huruf }}</span> / 
                            <span>{{ number_format($krs->nilai->bobot, 2) }}</span>
                        </td>
                        <td>
                            @if ($krs->nilai->status_lulus)
                                <span class="badge badge-success">Lulus</span>
                            @else
                                <span class="badge badge-danger">Belum Lulus</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('pengelola.input-nilai.edit', $krs) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada nilai terinput.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($nilaiList->hasPages())
        <div class="mt-4">
            <nav class="pagination" aria-label="Pagination">
                @if ($nilaiList->onFirstPage())
                    <span class="disabled">Sebelumnya</span>
                @else
                    <a href="{{ $nilaiList->appends(request()->query())->previousPageUrl() }}">Sebelumnya</a>
                @endif

                @foreach ($nilaiList->onEachSide(2)->linkCollection()->slice(1, -1) as $link)
                    @if ($link['url'] === null)
                        <span class="disabled">{!! $link['label'] !!}</span>
                    @elseif ($link['active'])
                        <span class="active">{!! $link['label'] !!}</span>
                    @else
                        <a href="{{ $link['url'] }}&{{ http_build_query(request()->except('page')) }}">{!! $link['label'] !!}</a>
                    @endif
                @endforeach

                @if ($nilaiList->hasMorePages())
                    <a href="{{ $nilaiList->appends(request()->query())->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span class="disabled">Berikutnya</span>
                @endif
            </nav>
        </div>
    @endif
</div>
@endsection
