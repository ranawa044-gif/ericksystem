@extends('layouts.app')

@section('title', ($dosen ? 'Edit' : 'Tambah') . ' Dosen')

@section('content')
<div class="mb-6 flex justify-between items-start flex-wrap gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('pengelola.dosen') }}" class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>Kembali ke Data Dosen</span>
            </a>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 m-0">{{ $dosen ? 'Edit Data Dosen' : 'Tambah Dosen Baru' }}</h1>
        <p class="text-muted text-sm mt-1">{{ $dosen ? 'Perbarui informasi data dosen dan kredensial akun login.' : 'Daftarkan dosen baru dan otomatis buatkan akun login ke sistem.' }}</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mb-6">
        <div class="font-bold mb-1">Terjadi kesalahan pengisian form:</div>
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card" style="max-width: 780px;">
    <form method="POST" action="{{ $dosen ? route('pengelola.dosen.update', $dosen) : route('pengelola.dosen.store') }}">
        @csrf
        @if ($dosen)
            @method('PUT')
        @endif

        <div class="mb-6">
            <h3 class="font-bold text-base text-slate-800 mb-1">Informasi Tenaga Pendidik</h3>
            <p class="text-xs text-muted">Data identitas resmi dosen pembimbing akademik.</p>
        </div>

        <div class="grid-2 mb-4">
            <div class="form-group">
                <label class="form-label" for="nidn">NIDN (Nomor Induk Dosen Nasional) <span class="text-danger">*</span></label>
                <input class="form-control @error('nidn') border-danger @enderror" id="nidn" type="text" name="nidn" value="{{ old('nidn', $dosen?->nidn ?? '') }}" placeholder="Contoh: 0018089402" required>
                @error('nidn')
                    <span class="text-xs text-danger mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="nama">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                <input class="form-control @error('nama') border-danger @enderror" id="nama" type="text" name="nama" value="{{ old('nama', $dosen?->nama ?? '') }}" placeholder="Contoh: Erick Febriyanto, S.Kom., M.T.I." required>
                @error('nama')
                    <span class="text-xs text-danger mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label" for="jabatan">Jabatan Fungsional / Peran <span class="text-danger">*</span></label>
            <input class="form-control @error('jabatan') border-danger @enderror" id="jabatan" type="text" name="jabatan" value="{{ old('jabatan', $dosen?->jabatan ?? '') }}" placeholder="Contoh: Dosen Wali, Ketua Program Studi, Dosen Pengajar" required>
            @error('jabatan')
                <span class="text-xs text-danger mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-6 pt-4" style="border-top: 1px solid var(--line);">
            <h3 class="font-bold text-base text-slate-800 mb-1">Akun Login Sistem</h3>
            <p class="text-xs text-muted">Kredensial yang digunakan dosen untuk masuk ke portal E-RICK.</p>
        </div>

        <div class="grid-2 mb-6">
            <div class="form-group">
                <label class="form-label" for="email">Email Login <span class="text-danger">*</span></label>
                <input class="form-control @error('email') border-danger @enderror" id="email" type="email" name="email" value="{{ old('email', $dosen?->user?->email ?? '') }}" placeholder="contoh: erick@luminara.id" required>
                @error('email')
                    <span class="text-xs text-danger mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">
                    Password {{ $dosen ? '' : '<span class="text-danger">*</span>' }}
                </label>
                <input class="form-control @error('password') border-danger @enderror" id="password" type="password" name="password" placeholder="{{ $dosen ? 'Kosongkan jika tidak diubah' : 'Minimal 6 karakter' }}" {{ $dosen ? '' : 'required' }}>
                @if($dosen)
                    <span class="text-xs text-muted mt-1 block">Biarkan kosong jika tetap menggunakan password lama.</span>
                @endif
                @error('password')
                    <span class="text-xs text-danger mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4" style="border-top: 1px solid var(--line);">
            <a href="{{ route('pengelola.dosen') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);">
                {{ $dosen ? 'Perbarui Data Dosen' : 'Simpan Dosen Baru' }}
            </button>
        </div>
    </form>
</div>
@endsection
