@extends('layouts.app')
@section('title', 'Pesan ke Dosen Wali')

@push('styles')
<style>
    .messages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }

    .message-card {
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .message-header {
        padding: 1rem;
        border-bottom: 1px solid var(--line);
        background: var(--page);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .message-body {
        padding: 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .message-text {
        color: var(--ink);
        line-height: 1.5;
        margin-bottom: 1rem;
        white-space: pre-wrap;
        font-size: 0.95rem;
        max-height: 250px;
        overflow-y: auto;
    }

    @media (min-width: 768px) {
        .pesan-layout {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1.5rem;
            align-items: start;
        }
    }
    
    @media (max-width: 767px) {
        .pesan-layout {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
    }
</style>
@endpush

@section('content')

<div class="mb-4">
    <h1 class="text-2xl font-semibold mb-2">Konsultasi Dosen Wali</h1>
    <p class="text-muted">Kirim saran, kritik, atau pertanyaan terkait akademik Anda.</p>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="pesan-layout">
    <div class="card" style="margin-bottom: 0;">
        <h2 class="font-semibold text-lg mb-4">Kirim Pesan Baru</h2>
        <form method="POST" action="{{ route('pesan.store', $mahasiswa->nim) }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="tipe">Tipe Pesan</label>
                <select class="form-select" id="tipe" name="tipe" required>
                    <option value="umum">Umum</option>
                    <option value="saran">Saran</option>
                    <option value="kritik">Kritik</option>
                    <option value="pertanyaan">Pertanyaan</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="pesan">Pesan</label>
                <textarea class="form-control" id="pesan" name="pesan" rows="5" required placeholder="Tulis pesan Anda di sini...">{{ old('pesan') }}</textarea>
            </div>

            <button class="btn btn-primary w-full" type="submit">Kirim Pesan</button>
        </form>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <h2 class="font-semibold text-lg mb-4">Riwayat Konsultasi</h2>
        
        @if ($pesan->count() > 0)
            <div class="messages-grid">
                @foreach ($pesan as $msg)
                    <div class="message-card">
                        <div class="message-header">
                            <div>
                                <h3 class="font-semibold text-md">Pesan Anda</h3>
                                <div class="text-sm text-muted mt-1">
                                    {{ $msg->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            <div>
                                <span class="badge badge-primary">{{ ucfirst($msg->tipe) }}</span>
                            </div>
                        </div>
                        
                        <div class="message-body">
                            <div class="message-text">{{ $msg->pesan }}</div>
                            
                            @if ($msg->balasan_dosen)
                                <div class="bg-blue-50 border border-blue-100 rounded-md p-3 mt-auto text-sm">
                                    <div class="flex items-center gap-2 mb-1 text-primary font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                        Balasan Dosen Wali ({{ $msg->dosen?->nama ?? 'Dosen Wali' }})
                                    </div>
                                    <p class="text-ink mb-1">{{ $msg->balasan_dosen }}</p>
                                    @if ($msg->dibalas_at)
                                        <div class="text-xs text-muted">Dibalas: {{ $msg->dibalas_at->format('d M Y, H:i') }}</div>
                                    @endif
                                </div>
                            @else
                                <div class="text-sm text-muted italic flex items-center gap-1 mt-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Menunggu balasan dosen wali...
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center text-center p-8">
                <svg style="color: var(--muted); margin-bottom: 1rem;" xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="font-semibold text-lg">Belum Ada Pesan</h3>
                <p class="text-muted">Anda belum mengirim pesan apapun ke dosen wali.</p>
            </div>
        @endif
    </div>
</div>

@endsection
