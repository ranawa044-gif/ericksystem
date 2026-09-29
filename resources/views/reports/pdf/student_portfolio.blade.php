<!DOCTYPE html>
<html>
<head>
    <title>Student Academic Portfolio</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 15px; margin-bottom: 25px; }
        .title { font-size: 22px; font-weight: bold; margin: 0; color: #1e3a8a; text-transform: uppercase; }
        .subtitle { font-size: 14px; margin: 5px 0 0 0; color: #64748b; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; margin-top: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; color: #1e40af; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 6px 0; vertical-align: top; }
        .info-table td:first-child { width: 150px; font-weight: bold; color: #475569; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        table.data-table th { background-color: #f8fafc; font-weight: bold; color: #334155; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .bg-blue { background-color: #eff6ff; color: #1d4ed8; }
        .bg-green { background-color: #f0fdf4; color: #15803d; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Student Academic Portfolio</h1>
        <p class="subtitle">E-RICK (Evaluasi Rekam Indeks, Capaian dan Konsultasi Akademik)</p>
    </div>

    <div class="section-title">1. Biodata Mahasiswa</div>
    <table class="info-table">
        <tr>
            <td>Nama Lengkap</td>
            <td>: {{ $mahasiswa->nama }}</td>
            <td rowspan="5" style="text-align: right;">
                @if($mahasiswa->foto)
                @php
                    $fotoPath = public_path('uploads/' . $mahasiswa->foto);
                    $fotoBase64 = null;
                    if (file_exists($fotoPath)) {
                        $fotoData = file_get_contents($fotoPath);
                        $fotoType = pathinfo($fotoPath, PATHINFO_EXTENSION);
                        $fotoBase64 = 'data:image/' . $fotoType . ';base64,' . base64_encode($fotoData);
                    }
                @endphp
                @if($fotoBase64)
                    <img src="{{ $fotoBase64 }}" alt="Foto Profil" style="width: 100px; height: 120px; object-fit: cover; border: 1px solid #ccc; display: inline-block;">
                @else
                    <div style="width: 100px; height: 120px; border: 1px solid #ccc; display: inline-block; text-align: center; line-height: 120px;">[Foto]</div>
                @endif
                @else
                    <div style="width: 100px; height: 120px; border: 1px solid #ccc; display: inline-block; text-align: center; line-height: 120px;">[Foto]</div>
                @endif
            </td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>: {{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>: {{ $mahasiswa->program_studi }}</td>
        </tr>
        <tr>
            <td>Angkatan</td>
            <td>: {{ $mahasiswa->angkatan }}</td>
        </tr>
        <tr>
            <td>Dosen Wali</td>
            <td>: {{ $mahasiswa->dosenWali->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>IPK Kumulatif</td>
            <td>: <strong style="font-size: 16px; color: #2563eb;">{{ number_format($mahasiswa->ipk, 2) }}</strong></td>
        </tr>
    </table>

    <div class="section-title">2. Perkembangan Studi per Semester (IPS)</div>
    @if(count($grafikData) > 0)
    <table class="data-table" style="width: 60%;">
        <thead>
            <tr>
                <th width="60%">Semester</th>
                <th width="40%" style="text-align: center;">Indeks Prestasi Semester (IPS)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grafikData as $data)
            <tr>
                <td>{{ $data['semester'] }}</td>
                <td style="text-align: center; font-weight: bold;">{{ number_format($data['ips'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Belum ada data nilai per semester.</p>
    @endif

    <div class="section-title">3. Daftar Nilai Mata Kuliah</div>
    @php
        $krsGrouped = $mahasiswa->krs->groupBy('semester_id');
    @endphp

    @if($krsGrouped->count() > 0)
        @foreach($krsGrouped as $semesterId => $krsList)
            @php 
                $semName = $krsList->first()->semester->nama ?? 'Semester ' . $semesterId; 
                $totalSks = $krsList->sum(fn($k) => $k->mataKuliah->sks);
            @endphp
            <div style="font-weight: bold; margin-bottom: 5px; background: #f1f5f9; padding: 5px;">{{ $semName }} (Total SKS: {{ $totalSks }})</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="15%">Kode</th>
                        <th width="45%">Mata Kuliah</th>
                        <th width="10%" style="text-align: center;">SKS</th>
                        <th width="15%" style="text-align: center;">Huruf Mutu</th>
                        <th width="15%" style="text-align: center;">Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($krsList as $krs)
                    <tr>
                        <td>{{ $krs->mataKuliah->kode }}</td>
                        <td>{{ $krs->mataKuliah->nama }}</td>
                        <td style="text-align: center;">{{ $krs->mataKuliah->sks }}</td>
                        <td style="text-align: center;">{{ $krs->nilai->nilai_huruf ?? '-' }}</td>
                        <td style="text-align: center;">{{ $krs->nilai->bobot ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @else
    <p>Belum ada data nilai mata kuliah.</p>
    @endif

    <div class="section-title">4. Riwayat Prestasi</div>
    @if($mahasiswa->prestasi->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="40%">Nama Prestasi</th>
                <th width="20%">Jenis Prestasi</th>
                <th width="15%">Tempat</th>
                <th width="20%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa->prestasi as $index => $prestasi)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $prestasi->nama_kegiatan }}</td>
                <td>{{ $prestasi->jenis_kegiatan }}</td>
                <td>{{ $prestasi->tempat }}</td>
                <td>{{ \Carbon\Carbon::parse($prestasi->tanggal)->format('d F Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Belum ada data prestasi yang dicatat.</p>
    @endif

    <div class="section-title">5. Riwayat Konsultasi Dosen Wali</div>
    @if($mahasiswa->pesanKeMahasiswa->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tanggal</th>
                <th width="40%">Subjek</th>
                <th width="35%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa->pesanKeMahasiswa as $index => $pesan)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $pesan->created_at->format('d M Y H:i') }}</td>
                <td>{{ Str::limit($pesan->isi_pesan, 50) }}</td>
                <td>
                    @if($pesan->balasan)
                        <span class="badge bg-green">Selesai (Dibalas)</span>
                    @else
                        <span class="badge bg-blue">Menunggu Balasan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Belum ada riwayat konsultasi.</p>
    @endif

    <div style="margin-top: 40px; text-align: right; border-top: 1px solid #ccc; padding-top: 10px;">
        <p style="margin: 0;">Dicetak oleh: {{ $mahasiswa->nama }}</p>
        <p style="margin: 0; font-size: 10px; color: #666;">Tanggal Cetak: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
