@extends('layouts.app')
@section('title', 'Prestasi dan Kegiatan')

@push('styles')
<style>
    .achievement-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .achievement-card {
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .achievement-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    
    .achievement-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: var(--primary);
    }

    .achievement-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-full);
        background: var(--primary-light, #e0f2fe);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    .achievement-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--warning);
        color: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .achievement-type-Seminar { background: #f3e8ff; color: #9333ea; }
    .achievement-type-Workshop { background: #dcfce7; color: #16a34a; }
    .achievement-type-Lomba { background: #fef9c3; color: #ca8a04; }
    .achievement-type-Organisasi { background: #fee2e2; color: #dc2626; }
    .achievement-type-Pengabdian { background: #e0f2fe; color: #0284c7; }

    .proof-thumb {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        margin-top: 1rem;
        border: 1px solid var(--line);
    }
</style>
@endpush

@section('content')

<div class="mb-4">
    <h1 class="text-2xl font-semibold mb-2">Portofolio & Prestasi</h1>
    <p class="text-muted">Catat dan pantau sertifikat lomba, kegiatan, dan organisasi.</p>
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

<div class="grid-3 mb-4">
    <!-- Form Tambah Prestasi -->
    <div class="card" style="grid-column: span 1;">
        <h2 class="font-semibold text-lg mb-4">Tambah Prestasi</h2>
        @if (auth()->user()->role === 'mahasiswa' && auth()->user()->mahasiswa?->id === $mahasiswa->id)
            <form method="POST" action="{{ route('mahasiswa.prestasi.store', ['nim' => $mahasiswa->nim]) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nama_kegiatan">Nama Kegiatan</label>
                    <input class="form-control" type="text" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required placeholder="Contoh: Juara 1 Web Design">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="jenis_kegiatan">Jenis</label>
                    <select class="form-select" id="jenis_kegiatan" name="jenis_kegiatan">
                        <option value="">Pilih jenis</option>
                        @foreach (['Lomba', 'Seminar', 'Workshop', 'Organisasi', 'Pengabdian', 'Lainnya'] as $jenis)
                            <option value="{{ $jenis }}" @selected(old('jenis_kegiatan') === $jenis)>{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="tanggal">Tanggal</label>
                    <input class="form-control" type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="tempat">Tempat</label>
                    <input class="form-control" type="text" id="tempat" name="tempat" value="{{ old('tempat') }}" placeholder="Contoh: Universitas Indonesia">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="pembimbing">Pembimbing</label>
                    <input class="form-control" type="text" id="pembimbing" name="pembimbing" value="{{ old('pembimbing') }}" placeholder="Nama dosen/pembimbing">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="bukti_file">Foto/Sertifikat (Max 5MB)</label>
                    <input class="form-control" type="file" id="bukti_file" name="bukti_file" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="keterangan">Keterangan Tambahan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-full mt-2">Simpan Prestasi</button>
            </form>
        @else
            <div class="alert alert-secondary text-sm">Hanya mahasiswa pemilik akun yang dapat menambah data prestasi. Pengelola hanya dapat melihat.</div>
        @endif
    </div>

    <!-- Daftar Prestasi -->
    <div style="grid-column: span 2;">
        <h2 class="font-semibold text-lg mb-4">Daftar Portofolio ({{ $prestasi->count() }})</h2>
        
        @if($prestasi->isEmpty())
            <div class="card flex flex-col items-center justify-center text-center" style="min-height: 300px;">
                <svg style="color: var(--muted); margin-bottom: 1rem;" xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <h3 class="font-semibold text-lg">Belum ada portofolio</h3>
                <p class="text-muted">Portofolio atau prestasi yang diinput akan muncul di sini.</p>
            </div>
        @else
            <div class="achievement-grid">
                @foreach ($prestasi as $item)
                    <div class="achievement-card">
                        @if ($item->jenis_kegiatan)
                            <div class="achievement-badge achievement-type-{{ $item->jenis_kegiatan }}">{{ $item->jenis_kegiatan }}</div>
                        @endif
                        
                        <div class="achievement-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        
                        <h3 class="font-semibold text-lg mb-1">{{ $item->nama_kegiatan }}</h3>
                        
                        @if ($item->tanggal)
                            <div class="text-sm text-muted mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $item->tanggal->format('d M Y') }}
                            </div>
                        @endif
                        
                        <div class="flex flex-col gap-1 text-sm mt-auto">
                            @if($item->tempat)
                                <div><span class="text-muted">Tempat:</span> {{ $item->tempat }}</div>
                            @endif
                            @if($item->pembimbing)
                                <div><span class="text-muted">Pembimbing:</span> {{ $item->pembimbing }}</div>
                            @endif
                            @if($item->keterangan)
                                <div class="mt-2 text-muted italic">"{{ $item->keterangan }}"</div>
                            @endif
                        </div>
                        
                        @if ($item->bukti_file)
                            @php
                                $extension = strtolower(pathinfo($item->bukti_file, PATHINFO_EXTENSION));
                                $buktiUrl = asset('uploads/prestasi/' . $item->bukti_file);
                            @endphp
                            
                            @if (in_array($extension, ['jpg', 'jpeg', 'png'], true))
                                <a href="{{ $buktiUrl }}" target="_blank" rel="noopener">
                                    <img src="{{ $buktiUrl }}" alt="Bukti {{ $item->nama_kegiatan }}" class="proof-thumb hoverable">
                                </a>
                            @else
                                <a href="{{ $buktiUrl }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm w-full mt-3 flex justify-center items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    Lihat Sertifikat PDF
                                </a>
                            @endif
                        @endif

                        @if (auth()->user()->role === 'mahasiswa' && auth()->user()->mahasiswa?->id === $mahasiswa->id)
                            <form method="POST" action="{{ route('mahasiswa.prestasi.destroy', ['nim' => $mahasiswa->nim, 'prestasi' => $item]) }}" class="mt-3" onsubmit="return confirm('Hapus data prestasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-full flex justify-center items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection
