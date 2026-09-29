<!DOCTYPE html>
<html>
<head>
    <title>Prestasi Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #f59e0b; padding-bottom: 15px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; margin: 0; color: #b45309; text-transform: uppercase; }
        .subtitle { font-size: 13px; margin: 5px 0 0 0; color: #78350f; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #d1d5db; padding: 6px; text-align: left; vertical-align: top; }
        table.data-table th { background-color: #f3f4f6; font-weight: bold; color: #374151; }
        .footer { margin-top: 40px; font-size: 10px; color: #6b7280; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Data Prestasi Mahasiswa</h1>
        <p class="subtitle">E-RICK Academic Report Center</p>
    </div>

    @if($prestasis->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIM</th>
                <th width="20%">Nama Mahasiswa</th>
                <th width="25%">Nama Prestasi</th>
                <th width="10%">Tingkat</th>
                <th width="10%">Kategori</th>
                <th width="10%">Peringkat</th>
                <th width="5%">Bukti</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestasis as $index => $prestasi)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $prestasi->mahasiswa->nim }}</td>
                <td>{{ $prestasi->mahasiswa->nama }}</td>
                <td>{{ $prestasi->nama_kegiatan }}<br><small style="color: #6b7280;">Tgl: {{ \Carbon\Carbon::parse($prestasi->tanggal)->format('d/m/Y') }}</small></td>
                <td>{{ $prestasi->jenis_kegiatan }}</td>
                <td>{{ $prestasi->tempat }}</td>
                <td>{{ $prestasi->keterangan }}</td>
                <td style="text-align: center;">
                    @if($prestasi->bukti_url)
                        Ada
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total Data:</strong> {{ $prestasis->count() }} Prestasi</p>
    @else
    <p style="text-align: center; padding: 20px; background: #f9fafb; border: 1px dashed #d1d5db;">Belum ada data prestasi yang sesuai dengan kriteria filter.</p>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i:s') }}
    </div>
</body>
</html>
