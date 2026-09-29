@extends('layouts.app')
@section('title', 'Data Mahasiswa')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-semibold mb-2">Data Mahasiswa</h1>
    @if (auth()->user()->role === 'dosen')
        <p class="text-muted">Daftar mahasiswa yang menjadi wali akademik Anda. Hanya mahasiswa wali Anda yang ditampilkan.</p>
    @else
        <p class="text-muted">Kelola data mahasiswa dan akun login mahasiswa.</p>
    @endif
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (auth()->user()->role === 'admin')
<div class="grid-2 mb-4">
    <div class="card" style="margin-bottom: 0;">
        <h2 class="font-semibold text-lg mb-4">Import Mahasiswa dari Excel</h2>
        <form method="POST" action="{{ route('pengelola.import.mahasiswa') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label" for="file_excel">File Excel (.xlsx)</label>
                <input class="form-control" type="file" id="file_excel" name="file" accept=".xlsx" required>
                <a href="{{ route('pengelola.import.mahasiswa.template') }}" class="text-sm text-primary mt-2 inline-block" style="text-decoration: underline;">Download Template</a>
            </div>
            <button class="btn btn-secondary w-full" type="submit">Upload Mahasiswa</button>
        </form>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <h2 class="font-semibold text-lg mb-4">Tambah Mahasiswa Baru</h2>
        <form method="POST" action="{{ route('pengelola.mahasiswa.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="nim">NIM</label>
                    <input class="form-control" id="nim" name="nim" value="{{ old('nim') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="nama">Nama</label>
                    <input class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="program_studi">Program Studi</label>
                    <input class="form-control" id="program_studi" name="program_studi" value="{{ old('program_studi', 'Sistem Informasi') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="angkatan">Angkatan</label>
                    <input class="form-control" id="angkatan" type="number" name="angkatan" min="2000" max="2100" value="{{ old('angkatan', date('Y')) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="dosen_wali_id">Dosen Wali</label>
                    <select class="form-select" id="dosen_wali_id" name="dosen_wali_id" required>
                        <option value="">Pilih dosen wali</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected(old('dosen_wali_id') == $dosen->id)>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Login</label>
                    <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}">
                    <span class="text-sm text-muted mt-1 block">Kosongkan untuk: NIM@student.local</span>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password Login</label>
                    <input class="form-control" id="password" type="text" name="password" value="{{ old('password') }}">
                    <span class="text-sm text-muted mt-1 block">Kosongkan untuk default: password</span>
                </div>
                <div class="form-group">
                    <label class="form-label" for="foto">Foto Mahasiswa</label>
                    <input class="form-control" id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/jpg">
                    <span class="text-sm text-muted mt-1 block">Maksimal: 5 MB</span>
                </div>
            </div>
            <button class="btn btn-primary mt-4 w-full" type="submit">Simpan Mahasiswa</button>
        </form>
    </div>
</div>
@endif

<div class="card mb-4">
    <h2 class="font-semibold text-lg mb-4">Filter Data</h2>
    <form method="GET" action="{{ route('pengelola.mahasiswa') }}">
        <div class="flex gap-4 items-end flex-wrap">
            @if(auth()->user()->role === 'admin')
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                <label class="form-label" for="filter_dosen">Dosen Wali</label>
                <select class="form-select" name="dosen_wali_id" id="filter_dosen">
                    <option value="">Semua Dosen</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" @selected(request('dosen_wali_id') == $dosen->id)>{{ $dosen->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                <label class="form-label" for="filter_angkatan">Angkatan</label>
                <select class="form-select" name="angkatan" id="filter_angkatan">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatans as $angkatan)
                        <option value="{{ $angkatan }}" @selected(request('angkatan') == $angkatan)>{{ $angkatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                <label class="form-label" for="filter_prodi">Program Studi</label>
                <select class="form-select" name="program_studi" id="filter_prodi">
                    <option value="">Semua Program Studi</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi }}" @selected(request('program_studi') == $prodi)>{{ $prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-secondary" type="submit">Filter</button>
                @if(request('dosen_wali_id') || request('angkatan') || request('program_studi'))
                    <a href="{{ route('pengelola.mahasiswa') }}" class="btn btn-danger">Reset</a>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="card">
    <h2 class="font-semibold text-lg mb-4">Daftar Mahasiswa</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Angkatan</th>
                    <th>Dosen Wali</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswas as $mahasiswa)
                    <tr>
                        <td class="font-semibold">{{ $mahasiswa->nim }}</td>
                        <td>{{ $mahasiswa->nama }}</td>
                        <td>{{ $mahasiswa->program_studi }}</td>
                        <td>{{ $mahasiswa->angkatan }}</td>
                        <td>{{ $mahasiswa->dosenWali->nama }}</td>
                        <td class="text-right">
                            <a href="{{ route('dashboard.mahasiswa', ['nim' => $mahasiswa->nim]) }}" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Buka</a>
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('pengelola.mahasiswa.edit', $mahasiswa) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Edit</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data mahasiswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($mahasiswas->hasPages())
        <div class="mt-4">
            <nav class="pagination" aria-label="Pagination data mahasiswa">
                @if ($mahasiswas->onFirstPage())
                    <span class="disabled">Sebelumnya</span>
                @else
                    <a href="{{ $mahasiswas->appends(request()->query())->previousPageUrl() }}">Sebelumnya</a>
                @endif

                @foreach ($mahasiswas->onEachSide(2)->linkCollection()->slice(1, -1) as $link)
                    @if ($link['url'] === null)
                        <span class="disabled">{!! $link['label'] !!}</span>
                    @elseif ($link['active'])
                        <span class="active">{!! $link['label'] !!}</span>
                    @else
                        <a href="{{ $link['url'] }}&{{ http_build_query(request()->except('page')) }}">{!! $link['label'] !!}</a>
                    @endif
                @endforeach

                @if ($mahasiswas->hasMorePages())
                    <a href="{{ $mahasiswas->appends(request()->query())->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span class="disabled">Berikutnya</span>
                @endif
            </nav>
        </div>
    @endif
</div>
@endsection
