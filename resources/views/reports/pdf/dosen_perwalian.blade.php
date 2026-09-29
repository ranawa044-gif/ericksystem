<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perwalian Dosen</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 12px; color: #000; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #000; padding-bottom: 15px; }
        .title { font-size: 16px; font-weight: bold; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .info-table { width: 100%; margin-bottom: 25px; }
        .info-table td { padding: 4px 0; vertical-align: top; }
        .info-table td.label { width: 150px; font-weight: bold; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 6px 8px; text-align: left; vertical-align: top; }
        table.data-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; margin-top: 25px; text-transform: uppercase; background: #e0e0e0; padding: 5px 10px; border: 1px solid #000; }
        .summary-box { border: 1px solid #000; padding: 15px; margin-bottom: 20px; background: #fafafa; }
        .summary-box p { margin: 5px 0; }
        .summary-box strong { display: inline-block; width: 250px; }
        
        .signature-area { width: 100%; margin-top: 50px; page-break-inside: avoid; }
        .signature-table { width: 100%; border: none; }
        .signature-table td { border: none; text-align: center; vertical-align: bottom; height: 120px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">LAPORAN REKAP DATA KEGIATAN PERWALIAN</h1>
        <h2 class="title" style="margin-top: 5px;">TAHUN AKADEMIK {{ $data['tahun_akademik'] ?? '..........' }}</h2>
    </div>

    <!-- IDENTITAS PERWALIAN -->
    <table class="info-table">
        <tr>
            <td class="label">Jenis Perwalian</td>
            <td>: {{ $data['jenis_perwalian'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Dosen Wali</td>
            <td>: {{ $data['nama_dosen'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $data['kelas'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tingkat</td>
            <td>: {{ $data['tingkat'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Semester</td>
            <td>: {{ $data['semester'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Program Studi</td>
            <td>: {{ $data['program_studi'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jurusan</td>
            <td>: {{ $data['jurusan'] ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">A. STATUS MAHASISWA & PERWALIAN</div>
    <div class="summary-box">
        <p><strong>1. Jumlah mahasiswa aktif</strong> : {{ $data['jumlah_aktif'] ?? '0' }} mahasiswa</p>
        <p><strong>2. Jumlah mahasiswa cuti</strong> : {{ $data['jumlah_cuti'] ?? '0' }} mahasiswa</p>
        <p><strong>3. Jumlah mahasiswa mengundurkan diri</strong> : {{ $data['jumlah_keluar'] ?? '0' }} mahasiswa</p>
        <p><strong>4. Jumlah mahasiswa KIP</strong> : {{ $data['jumlah_kip'] ?? '0' }} mahasiswa</p>
        
        @if(!empty(trim($data['nama_kip'] ?? '')))
            <div style="margin-left: 20px; margin-top: 5px;">
                <em>Terdiri atas:</em><br>
                {!! nl2br(e($data['nama_kip'])) !!}
            </div>
        @endif
        
        <br>
        <p><strong>Mahasiswa Sudah Perwalian</strong> : {{ $data['sudah_perwalian'] ?? '0' }} mahasiswa</p>
        <p><strong>Mahasiswa Belum Perwalian</strong> : {{ $data['belum_perwalian'] ?? '0' }} mahasiswa</p>
    </div>

    <div class="section-title">B. STATUS AKADEMIK (PERINGATAN DINI)</div>
    <div class="summary-box">
        <p><strong>SP 1</strong> : {{ $data['sp1'] ?? '0' }} mahasiswa</p>
        <p><strong>SP 2</strong> : {{ $data['sp2'] ?? '0' }} mahasiswa</p>
        <p><strong>SP 3</strong> : {{ $data['sp3'] ?? '0' }} mahasiswa</p>
    </div>

    <div class="section-title">C. MAHASISWA PERLU PERHATIAN KHUSUS</div>
    <div style="border: 1px solid #000; padding: 10px; min-height: 80px; margin-bottom: 20px;">
        @if(!empty(trim($data['mahasiswa_perhatian'] ?? '')))
            {!! nl2br(e($data['mahasiswa_perhatian'])) !!}
        @else
            <em style="color: #666;">(Tidak ada mahasiswa yang memerlukan perhatian khusus)</em>
        @endif
    </div>

    <div class="section-title" style="page-break-before: auto;">D. PRESTASI MAHASISWA</div>
    <p><strong>Jumlah prestasi mahasiswa periode ini: {{ $data['jumlah_prestasi'] ?? 0 }}</strong></p>
    
    <p style="font-weight: bold; margin-top: 15px; margin-bottom: 5px;">1. Prestasi Akademik</p>
    <div style="margin-left: 20px;">
        @if(!empty(trim($data['prestasi_akademik'] ?? '')))
            {!! nl2br(e($data['prestasi_akademik'])) !!}
        @else
            - Belum ada prestasi akademik.
        @endif
    </div>

    <p style="font-weight: bold; margin-top: 15px; margin-bottom: 5px;">2. Prestasi Non-Akademik</p>
    <div style="margin-left: 20px;">
        @if(!empty(trim($data['prestasi_non_akademik'] ?? '')))
            {!! nl2br(e($data['prestasi_non_akademik'])) !!}
        @else
            - Belum ada prestasi non-akademik.
        @endif
    </div>

    <div class="section-title">E. CATATAN PERWALIAN</div>
    <div style="border: 1px solid #000; padding: 10px; min-height: 80px; text-align: justify;">
        @if(!empty(trim($data['catatan_perwalian'] ?? '')))
            {!! nl2br(e($data['catatan_perwalian'])) !!}
        @else
            <em style="color: #666;">(Tidak ada catatan)</em>
        @endif
    </div>

    <div class="section-title">F. PENGEMBANGAN DIRI MAHASISWA</div>
    <div style="border: 1px solid #000; padding: 10px; min-height: 80px; text-align: justify;">
        @if(!empty(trim($data['pengembangan_diri'] ?? '')))
            {!! nl2br(e($data['pengembangan_diri'])) !!}
        @else
            <em style="color: #666;">(Tidak ada catatan pengembangan diri)</em>
        @endif
    </div>

    <div class="section-title">G. JADWAL PERWALIAN BERIKUTNYA</div>
    <p>Tanggal/Jadwal: <strong>{{ !empty($data['jadwal_berikutnya']) ? \Carbon\Carbon::parse($data['jadwal_berikutnya'])->format('d F Y') : '........................................' }}</strong></p>

    <!-- TANDA TANGAN -->
    <div class="signature-area">
        <div style="text-align: right; margin-bottom: 10px;">
            Bandung, {{ now()->format('d F Y') }}
        </div>
        <div style="text-align: center; margin-bottom: 20px;">
            Mengetahui,
        </div>
        <table class="signature-table">
            <tr>
                <td width="50%">
                    Koordinator Program Studi<br>
                    {{ $data['program_studi'] ?? '' }}<br><br><br><br><br>
                    <span class="signature-name">{{ $data['nama_kaprodi'] ?? '........................................' }}</span>
                </td>
                <td width="50%">
                    Dosen Wali<br><br><br><br><br><br>
                    <span class="signature-name">{{ $data['nama_dosen'] ?? '........................................' }}</span>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
