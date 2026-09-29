@extends('layouts.app')

@section('title', 'SPK Analytics')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2 text-gray-800">SPK Analytics (Admin)</h1>
    <p class="text-muted">Distribusi status akademik seluruh mahasiswa dan pengaturan bobot kriteria TOPSIS.</p>
</div>

@if(session('success'))
<div class="p-4 mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg">
    {{ session('success') }}
</div>
@endif

<div class="grid" style="grid-template-columns: 2fr 1fr; gap: 20px;">
    <!-- Kolom Kiri: Analytics -->
    <div>
        <h3 class="font-semibold text-lg mb-4">Distribusi Status Akademik Global</h3>
        <div class="grid" style="grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <div class="card" style="border-top: 4px solid #10b981; text-align: center;">
                <h3 class="text-sm font-semibold text-muted uppercase">Aman</h3>
                <p class="text-4xl font-bold mt-2 text-green-600">{{ $amanCount }}</p>
            </div>
            <div class="card" style="border-top: 4px solid #f59e0b; text-align: center;">
                <h3 class="text-sm font-semibold text-muted uppercase">Monitoring</h3>
                <p class="text-4xl font-bold mt-2 text-yellow-600">{{ $monitoringCount }}</p>
            </div>
            <div class="card" style="border-top: 4px solid #ef4444; text-align: center;">
                <h3 class="text-sm font-semibold text-muted uppercase">Pendampingan</h3>
                <p class="text-4xl font-bold mt-2 text-red-600">{{ $pendampinganCount }}</p>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold mb-2">Statistik Komparatif</h3>
            @php
                $total = $amanCount + $monitoringCount + $pendampinganCount;
                $pAman = $total > 0 ? round(($amanCount / $total) * 100, 1) : 0;
                $pMonitoring = $total > 0 ? round(($monitoringCount / $total) * 100, 1) : 0;
                $pPendampingan = $total > 0 ? round(($pendampinganCount / $total) * 100, 1) : 0;
            @endphp
            
            <div class="w-full bg-slate-200 rounded-full h-4 mb-2 flex overflow-hidden">
                <div class="bg-green-500 h-4" style="width: {{ $pAman }}%"></div>
                <div class="bg-yellow-500 h-4" style="width: {{ $pMonitoring }}%"></div>
                <div class="bg-red-500 h-4" style="width: {{ $pPendampingan }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-muted">
                <span>{{ $pAman }}% Aman</span>
                <span>{{ $pMonitoring }}% Monitoring</span>
                <span>{{ $pPendampingan }}% Pendampingan</span>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Settings -->
    <div>
        <div class="card">
            <h3 class="font-semibold text-lg mb-4">Pengaturan Bobot Kriteria (SPK)</h3>
            <p class="text-xs text-muted mb-4">Ubah persentase bobot tiap kriteria di bawah ini (Total desimal disarankan 1.00).</p>
            
            <form action="{{ route('advisor.admin.weights') }}" method="POST">
                @csrf
                @foreach($weights as $weight)
                <div class="mb-3">
                    <label class="font-medium text-sm block mb-1">
                        {{ $weight->code }} - {{ $weight->name }} 
                        <span class="text-xs text-muted">({{ ucfirst($weight->type) }})</span>
                    </label>
                    <input type="number" step="0.01" min="0" max="1" name="weights[{{ $weight->code }}]" class="form-control" value="{{ $weight->weight }}">
                </div>
                @endforeach
                
                <div class="mt-4 pt-4 border-t">
                    <button type="submit" class="btn btn-primary w-full text-center">
                        Simpan Bobot & Analisis Ulang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
