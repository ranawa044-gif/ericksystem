<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Mahasiswa - ERICK</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 {
            margin: 0 0 10px 0;
            text-align: center;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .dosen-section {
            margin-bottom: 30px;
        }
        .dosen-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #f0f0f0;
            padding: 5px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 5px 8px;
            vertical-align: top;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Print specific adjustments */
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            @page { size: landscape; margin: 10mm; }
        }

        .badge-rank {
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            background-color: #e0f2fe;
            color: #0369a1;
            border-radius: 50%;
            text-align: center;
            font-weight: bold;
        }
        .badge-status {
            display: inline-block;
            background-color: #dcfce7;
            color: #166534;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .course-list {
            margin: 0;
            padding: 0;
        }
        .course-item {
            display: inline-block;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            padding: 4px 6px;
            margin: 0 4px 4px 0;
            border-radius: 4px;
            font-size: 9px;
            color: #475569;
        }
        .course-name {
            color: #334155;
        }
        .course-grade {
            font-weight: bold;
            color: #0284c7;
            margin-left: 4px;
            border-left: 1px solid #e2e8f0;
            padding-left: 4px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>REKAPITULASI DATA MAHASISWA</h1>
        <h3>Sistem Akademik ERICK</h3>
        <p class="text-center">
            Berdasarkan: 
            @if($semesterId === 'all')
                Semua Semester (Kumulatif)
            @else
                @php
                    $sem = $semesters->firstWhere('id', $semesterId);
                @endphp
                {{ $sem ? 'Semester ' . $sem->urutan . ' - ' . $sem->tahun_akademik . ' (' . ucfirst($sem->periode) . ')' : '-' }}
            @endif
        </p>
    </div>

    @forelse($grouped as $mahasiswas)
        @php
            $dosenWali = optional($mahasiswas->first()->dosenWali)->nama ?? 'Tanpa Dosen Wali';
        @endphp

        <div class="dosen-section">
            <div class="dosen-name">Dosen Wali: {{ $dosenWali }} ({{ $mahasiswas->count() }} Mahasiswa)</div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">Rank</th>
                        <th style="width: 150px;">NIM & Nama</th>
                        @if($semesterId !== 'all')
                            <th style="width: 50px;">IPS</th>
                            <th style="width: 50px;">IPK</th>
                            <th style="width: 80px;">Status</th>
                        @else
                            <th style="width: 50px;">IPK</th>
                        @endif
                        <th>Detail Mata Kuliah & Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswas as $m)
                        @php
                            $krsData = $semesterId === 'all'
                                ? $m->krs->filter(fn($k) => $k->nilai)
                                : $m->krs->filter(fn($k) => $k->semester_id == $semesterId && $k->nilai);
                        @endphp
                        <tr>
                            <td class="text-center"><span class="badge-rank">{{ $m->rank ?? '-' }}</span></td>
                            <td>
                                <div class="font-bold">{{ $m->nim }}</div>
                                <div>{{ $m->nama }}</div>
                            </td>
                            @if($semesterId !== 'all')
                                <td class="text-center">{{ number_format($m->ips, 2) }}</td>
                                <td class="text-center font-bold">{{ number_format($m->ipk, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge-status">{{ $m->status_semester ?? '-' }}</span>
                                </td>
                            @else
                                <td class="text-center font-bold">{{ number_format($m->ipk, 2) }}</td>
                            @endif
                            <td>
                                @if($krsData->isEmpty())
                                    <span style="color: #999; font-style: italic;">Belum ada data nilai.</span>
                                @else
                                     <div class="course-list">
                                        @foreach($krsData as $krs)
                                            <span class="course-item">
                                                <span class="course-name">{{ $krs->mataKuliah->nama }} ({{ $krs->mataKuliah->sks }} SKS)</span>
                                                <span class="course-grade">
                                                    {{ number_format($krs->nilai->nilai_angka, 2) }} {{ $krs->nilai->nilai_huruf ?? '-' }}
                                                </span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p class="text-center" style="padding: 50px; border: 1px dashed #ccc;">Tidak ada data mahasiswa untuk dicetak pada filter ini.</p>
    @endforelse

</body>
</html>
