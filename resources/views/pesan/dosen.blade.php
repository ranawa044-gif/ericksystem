@extends('layouts.app')
@section('title', 'Pesan dari Mahasiswa')

@push('styles')
<style>
    .messages-grid {
        display: grid;
        grid-template-columns: 1fr;
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
    }

    .reply-form {
        display: none;
        background: var(--page);
        padding: 1.25rem;
        border-top: 1px solid var(--line);
    }
</style>
@endpush

@section('content')

<div class="mb-4">
    <h1 class="text-2xl font-semibold mb-2">Pesan & Konsultasi Mahasiswa</h1>
    <p class="text-muted">Pesan yang dikirim oleh mahasiswa bimbingan Anda.</p>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card mb-4">
    <h2 class="font-semibold text-lg mb-4">Filter Pesan</h2>
    <form method="GET" action="{{ route('pesan.dosen') }}">
        <div class="flex gap-4 items-end flex-wrap">
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                <label class="form-label" for="q">Pencarian Mahasiswa</label>
                <input class="form-control" id="q" name="q" value="{{ request('q') }}" placeholder="Ketik NIM atau Nama">
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                <label class="form-label" for="tipe">Jenis Pesan</label>
                <select class="form-select" id="tipe" name="tipe">
                    <option value="">Semua Jenis</option>
                    <option value="umum" @selected(request('tipe') == 'umum')>Umum</option>
                    <option value="saran" @selected(request('tipe') == 'saran')>Saran</option>
                    <option value="kritik" @selected(request('tipe') == 'kritik')>Kritik</option>
                    <option value="pertanyaan" @selected(request('tipe') == 'pertanyaan')>Pertanyaan</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                <label class="form-label" for="tanggal">Tanggal Masuk</label>
                <input class="form-control" id="tanggal" type="date" name="tanggal" value="{{ request('tanggal') }}">
            </div>
            <div class="flex gap-2">
                <button class="btn btn-secondary" type="submit">Filter</button>
                @if(request('q') || request('tipe') || request('tanggal'))
                    <a href="{{ route('pesan.dosen') }}" class="btn btn-danger" style="display: inline-flex; align-items: center; padding: 0 1rem; border-radius: var(--radius-md);">Reset</a>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="card">
    @if ($pesan->count() > 0)
        <div class="messages-grid">
            @foreach ($pesan as $msg)
                <div class="message-card">
                    <div class="message-header">
                        <div>
                            <h3 class="font-semibold text-lg" style="margin-bottom: 0;">{{ $msg->mahasiswa->nama }}</h3>
                            <div class="text-sm text-muted mt-1 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                {{ $msg->mahasiswa->nim }}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-primary">{{ ucfirst($msg->tipe) }}</span>
                            <div class="text-xs text-muted mt-2">
                                {{ $msg->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="message-body">
                        <div class="message-text">{{ $msg->pesan }}</div>
                        
                        @if ($msg->balasan_dosen)
                            <div class="bg-blue-50 border border-blue-100 rounded-md p-3 mt-auto text-sm">
                                <div class="flex items-center gap-2 mb-1 text-primary font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                    Balasan Anda
                                </div>
                                <p class="text-ink mb-1">{{ $msg->balasan_dosen }}</p>
                                @if ($msg->dibalas_at)
                                    <div class="text-xs text-muted">Dibalas: {{ $msg->dibalas_at->format('d M Y, H:i') }}</div>
                                @endif
                            </div>
                        @endif

                        <div class="mt-3 text-right">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleReplyForm({{ $msg->id }})" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
                                {{ $msg->balasan_dosen ? 'Edit Balasan' : 'Balas' }}
                            </button>
                        </div>
                    </div>
                    
                    <form class="reply-form" id="reply-form-{{ $msg->id }}" method="POST" action="{{ route('pesan.dosen.balas', $msg) }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label text-sm" for="balasan_dosen_{{ $msg->id }}">
                                {{ $msg->balasan_dosen ? 'Edit Balasan' : 'Tulis Balasan' }}
                            </label>
                            <textarea class="form-control" id="balasan_dosen_{{ $msg->id }}" name="balasan_dosen" rows="2" required placeholder="Balasan untuk {{ $msg->mahasiswa->nama }}...">{{ old('balasan_dosen', $msg->balasan_dosen) }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;" type="submit">{{ $msg->balasan_dosen ? 'Update Balasan' : 'Kirim Balasan' }}</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

        @if (method_exists($pesan, 'hasPages') && $pesan->hasPages())
            <div class="mt-4">
                <nav class="pagination" aria-label="Pagination">
                    @if ($pesan->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $pesan->appends(request()->query())->previousPageUrl() }}">Sebelumnya</a>
                    @endif

                    @foreach ($pesan->appends(request()->query())->onEachSide(2)->linkCollection()->slice(1, -1) as $link)
                        @if ($link['url'] === null)
                            <span class="disabled">{!! $link['label'] !!}</span>
                        @elseif ($link['active'])
                            <span class="active">{!! $link['label'] !!}</span>
                        @else
                            <a href="{{ $link['url'] }}">{!! $link['label'] !!}</a>
                        @endif
                    @endforeach

                    @if ($pesan->hasMorePages())
                        <a href="{{ $pesan->appends(request()->query())->nextPageUrl() }}">Berikutnya</a>
                    @else
                        <span class="disabled">Berikutnya</span>
                    @endif
                </nav>
            </div>
        @endif
    @else
        <div class="flex flex-col items-center justify-center text-center p-8">
            <svg style="color: var(--muted); margin-bottom: 1rem;" xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="font-semibold text-lg">Kotak Masuk Kosong</h3>
            <p class="text-muted">Belum ada pesan masuk dari mahasiswa wali Anda atau tidak ada hasil yang cocok dengan filter.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function toggleReplyForm(id) {
        const form = document.getElementById('reply-form-' + id);
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
@endpush
@endsection
