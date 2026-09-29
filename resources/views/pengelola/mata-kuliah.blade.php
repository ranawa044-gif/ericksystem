@extends('layouts.app')
@section('title', 'Data Mata Kuliah')

@section('content')

<div class="mb-4 flex justify-between items-start flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-semibold mb-2">Data Mata Kuliah</h1>
        <p class="text-muted">Admin menambahkan mata kuliah sebagai dasar input nilai mahasiswa.</p>
    </div>
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

<div class="card mb-4">
    <h2 class="font-semibold text-lg mb-4">Tambah Mata Kuliah</h2>
    <form method="POST" action="{{ route('pengelola.mata-kuliah.store') }}">
        @csrf
        <div class="grid-4 mb-4">
            <div class="form-group">
                <label class="form-label" for="kode">Kode</label>
                <input class="form-control" id="kode" name="kode" value="{{ old('kode') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama">Nama Mata Kuliah</label>
                <input class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="sks">SKS</label>
                <input class="form-control" id="sks" type="number" name="sks" min="1" max="6" value="{{ old('sks', 3) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="semester_default">Semester</label>
                <input class="form-control" id="semester_default" type="number" name="semester_default" min="1" max="14" value="{{ old('semester_default', 1) }}" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Simpan Mata Kuliah</button>
    </form>
</div>

<div class="card">
    <h2 class="font-semibold text-lg mb-4">Daftar Mata Kuliah</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mataKuliahs as $mataKuliah)
                    <tr>
                        <td class="font-semibold">{{ $mataKuliah->kode }}</td>
                        <td>{{ $mataKuliah->nama }}</td>
                        <td>{{ $mataKuliah->sks }}</td>
                        <td>{{ $mataKuliah->semester_default }}</td>
                        <td class="text-right">
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('pengelola.mata-kuliah.edit', $mataKuliah) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Edit</a>
                                <form method="POST" action="{{ route('pengelola.mata-kuliah.destroy', $mataKuliah) }}" onsubmit="return confirm('Hapus mata kuliah ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data mata kuliah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
