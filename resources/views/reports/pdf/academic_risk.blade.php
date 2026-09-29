<!DOCTYPE html>
<html>
<head>
    <title>Academic Risk Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #ef4444; padding-bottom: 15px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; margin: 0; color: #b91c1c; text-transform: uppercase; }
        .subtitle { font-size: 13px; margin: 5px 0 0 0; color: #7f1d1d; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #fca5a5; padding: 8px; text-align: left; vertical-align: top; }
        table.data-table th { background-color: #fef2f2; font-weight: bold; color: #991b1b; }
        .text-danger { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 10px; color: #6b7280; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Academic Risk Report</h1>
        <p class="subtitle">Daftar Mahasiswa Memerlukan Perhatian Khusus</p>
    </div>

    <div style="margin-bottom: 15px; background: #fff5f5; padding: 10px; border-left: 4px solid #ef4444;">
        <strong>Kriteria Risiko:</strong> Mahasiswa masuk dalam daftar ini jika memiliki IPK Kumulatif di bawah 3.00, ATAU mengalami penurunan Indeks Prestasi Semester (IPS) terakhir dibandingkan semester sebelumnya.
    </div>

    @if(count($risikoMahasiswa) > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIM</th>
                <th width="25%">Nama Mahasiswa</th>
                <th width="10%">IPK</th>
                <th width="25%">Alasan Risiko</th>
                <th width="20%">Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($risikoMahasiswa as $index => $mhs)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>
                    <strong>{{ $mhs->nama }}</strong><br>
                    <small style="color: #666;">Wali: {{ $mhs->dosenWali->nama ?? '-' }}</small>
                </td>
                <td style="text-align: center;" class="{{ $mhs->ipk < 3.0 ? 'text-danger' : '' }}">{{ number_format($mhs->ipk, 2) }}</td>
                <td>{{ $mhs->alasan_risiko }}</td>
                <td>Segera jadwalkan sesi konsultasi akademik dengan Dosen Wali.</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total Mahasiswa Berisiko:</strong> {{ count($risikoMahasiswa) }} Mahasiswa</p>
    @else
    <p style="text-align: center; padding: 20px; background: #f0fdf4; border: 1px dashed #22c55e; color: #15803d;">
        <strong>Kondisi Ideal!</strong> Saat ini tidak ada mahasiswa yang terdeteksi memerlukan perhatian khusus berdasarkan kriteria risiko akademik.
    </p>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i:s') }}
    </div>
</body>
</html>
