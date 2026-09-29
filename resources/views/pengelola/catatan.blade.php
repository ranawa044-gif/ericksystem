@extends('layouts.app')
@section('title', 'Catatan Dosen Wali')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-semibold mb-2">Catatan Dosen Wali</h1>
    <p class="text-muted">Catatan yang disimpan di sini akan tampil di dashboard mahasiswa.</p>
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

<div class="grid-2 mb-4">
    <div class="card" style="margin-bottom: 0;">
        <h2 class="font-semibold text-lg mb-4">Tambah Catatan</h2>
        <form method="POST" action="{{ route('pengelola.catatan.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="mahasiswa_id">Mahasiswa</label>
                <select class="form-select" id="mahasiswa_id" name="mahasiswa_id" required>
                    <option value="">Pilih mahasiswa</option>
                    @foreach ($mahasiswas as $mahasiswa)
                        <option value="{{ $mahasiswa->id }}" @selected(old('mahasiswa_id') == $mahasiswa->id)>
                            {{ $mahasiswa->nim }} - {{ $mahasiswa->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="tanggal">Tanggal</label>
                <input class="form-control" id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required @if(auth()->user()->role === 'dosen') readonly style="background-color: #f8fafc; color: var(--muted); cursor: not-allowed;" title="Dosen wali tidak dapat mengubah tanggal" @endif>
            </div>

            <div class="form-group">
                <label class="form-label" for="catatan">Isi Catatan</label>
                <textarea class="form-control" id="catatan" name="catatan" rows="4" required>{{ old('catatan') }}</textarea>
            </div>

            <button class="btn btn-primary w-full" type="submit">Simpan Catatan</button>
        </form>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <h2 class="font-semibold text-lg mb-4">Riwayat Catatan</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="min-width: 100px;">Tanggal</th>
                        <th style="min-width: 150px;">Mahasiswa</th>
                        <th>Dosen</th>
                        <th style="min-width: 200px;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($catatanList as $catatan)
                        <tr>
                            <td class="font-semibold">{{ $catatan->tanggal->format('d M Y') }}</td>
                            <td>
                                <div>{{ $catatan->mahasiswa->nim }}</div>
                                <div class="text-sm text-muted">{{ $catatan->mahasiswa->nama }}</div>
                            </td>
                            <td>{{ $catatan->dosen->nama }}</td>
                            <td>{{ $catatan->catatan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada catatan tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
