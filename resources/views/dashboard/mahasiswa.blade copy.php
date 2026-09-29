<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Nilai Mahasiswa</title>
    <style>
        :root {
            color-scheme: light;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            --primary: #38bdf8; /* biru muda */
            --primary-dark: #0ea5e9;
            --accent: #0ea5e9;
            --ink: #172026;
            --muted: #667085;
            --line: #d8dee4;
            --panel: #ffffff;
            --page: #e0f2fe; /* biru muda sangat terang */
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            color: var(--ink);
        }

        .shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 36px 0 44px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 28px;
        }

        .session-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 14px;
        }

        .session-actions a,
        .session-actions button {
            min-height: 36px;
            border-radius: 6px;
            font-weight: 700;
            padding: 8px 11px;
        }

        .session-actions a {
            display: inline-flex;
            align-items: center;
            color: #ffffff;
            background: linear-gradient(90deg, var(--primary) 60%, var(--primary-dark) 100%);
            text-decoration: none;
            box-shadow: 0 2px 8px 0 rgba(56, 189, 248, 0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .session-actions a:hover {
            background: linear-gradient(90deg, var(--primary-dark) 60%, var(--primary) 100%);
            box-shadow: 0 4px 16px 0 rgba(56, 189, 248, 0.18);
        }

        .session-actions button {
            border: 1.5px solid var(--primary);
            background: #f0f9ff;
            color: var(--primary-dark);
            cursor: pointer;
            font-weight: 700;
            transition: background 0.2s, color 0.2s;
        }
        .session-actions button:hover {
            background: var(--primary);
            color: #fff;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .identity,
        .panel,
        .metric {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 8px 32px 0 rgba(56, 189, 248, 0.10), 0 1.5px 8px 0 rgba(14, 165, 233, 0.08);
        }

        .identity {
            padding: 16px;
            min-width: 300px;
        }

        .identity dl {
            display: grid;
            grid-template-columns: 110px 1fr;
            gap: 8px 12px;
            margin: 0;
            font-size: 14px;
        }

        dt {
            color: var(--muted);
        }

        dd {
            margin: 0;
            font-weight: 600;
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .metric {
            padding: 16px;
        }

        .metric span {
            display: block;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 8px;
        }

        .metric strong {
            font-size: 28px;
            color: var(--primary-dark);
            font-weight: 800;
        }

        .grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 18px;
        }

        .panel {
            padding: 22px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        h2 {
            margin: 0;
            font-size: 19px;
        }

        select {
            border: 1.5px solid var(--primary);
            border-radius: 8px;
            padding: 10px 12px;
            min-width: 190px;
            background: #f0f9ff;
            color: var(--ink);
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            border-bottom: 1px solid var(--line);
            padding: 12px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            min-height: 24px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-pass {
            color: #166534;
            background: #dcfce7;
        }

        .badge-fail {
            color: #991b1b;
            background: #fee2e2;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .summary div {
            border: 1px solid var(--primary);
            border-radius: 10px;
            padding: 16px;
            background: #f0f9ff;
        }

        .summary span {
            display: block;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 6px;
        }

        .summary strong {
            font-size: 26px;
            color: var(--primary-dark);
            font-weight: 700;
        }

        .notes {
            display: grid;
            gap: 12px;
        }

        .note {
            border-left: 4px solid var(--primary-dark);
            padding: 14px 14px 14px 18px;
            background: #f0f9ff;
            border-radius: 8px;
        }

        .note time {
            display: block;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 6px;
        }

        .note p {
            margin: 0 0 8px;
            line-height: 1.5;
        }

        .note small {
            color: var(--muted);
        }

        .empty {
            color: var(--muted);
            padding: 18px 0;
        }

        .alert-success {
            margin-bottom: 16px;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 14px;
            color: #166534;
            background: #f0fdf4;
        }

        @media (max-width: 900px) {
            .topbar,
            .panel-header {
                flex-direction: column;
            }

            .identity,
            select {
                width: 100%;
            }

            .metrics,
            .grid {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="topbar">
            <div>
                <h1>Dashboard Nilai Mahasiswa</h1>
                <p>Rekap akademik, nilai per semester, IPS, IPK, dan catatan dosen wali.</p>
                <div class="session-actions">
                    <a href="{{ route('home') }}">Beranda</a>
                    @if (auth()->user()->role === 'mahasiswa')
                        <a href="{{ route('mahasiswa.password.edit') }}">Ganti Password</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>

            <aside class="identity" aria-label="Identitas mahasiswa">
                <dl>
                    <dt>NIM</dt>
                    <dd>{{ $mahasiswa->nim }}</dd>
                    <dt>Nama</dt>
                    <dd>{{ $mahasiswa->nama }}</dd>
                    <dt>Program Studi</dt>
                    <dd>{{ $mahasiswa->program_studi }}</dd>
                    <dt>Angkatan</dt>
                    <dd>{{ $mahasiswa->angkatan }}</dd>
                    <dt>Dosen Wali</dt>
                    <dd>{{ $mahasiswa->dosenWali->nama }}</dd>
                </dl>
            </aside>
        </section>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <section class="metrics" aria-label="Ringkasan akademik">
            <div class="metric">
                <span>IPK Kumulatif</span>
                <strong>{{ number_format($ipk, 2) }}</strong>
            </div>
            <div class="metric">
                <span>Total SKS Ditempuh</span>
                <strong>{{ $totalSks }}</strong>
            </div>
            <div class="metric">
                <span>Mata Kuliah Lulus</span>
                <strong>{{ $jumlahLulus }}</strong>
            </div>
            <div class="metric">
                <span>Belum Lulus</span>
                <strong>{{ $jumlahBelumLulus }}</strong>
            </div>
        </section>

        <section class="grid">
            <article class="panel">
                <div class="panel-header">
                    <h2>Rekap Nilai Per Semester</h2>
                    <form method="GET" action="{{ route('dashboard.mahasiswa', $mahasiswa->nim) }}">
                        <select name="semester_id" onchange="this.form.submit()" aria-label="Filter semester">
                            <option value="all" @selected($isAllSemesters)>
                                Semua Semester
                            </option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}" @selected($semester->id === $selectedSemesterId && !$isAllSemesters)>
                                    {{ $semester->nama }} - {{ $semester->tahun_akademik }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @if ($nilaiSemester->isEmpty())
                    <div class="empty">Belum ada nilai untuk semester ini.</div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Nilai Angka</th>
                                <th>Huruf</th>
                                <th>Bobot</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nilaiSemester as $krs)
                                <tr>
                                    <td>{{ $krs->mataKuliah->kode }}</td>
                                    <td>{{ $krs->mataKuliah->nama }}</td>
                                    <td>{{ $krs->mataKuliah->sks }}</td>
                                    <td>{{ number_format($krs->nilai?->nilai_angka ?? 0, 2) }}</td>
                                    <td>{{ $krs->nilai?->nilai_huruf ?? '-' }}</td>
                                    <td>{{ number_format($krs->nilai?->bobot ?? 0, 2) }}</td>
                                    <td>
                                        @if ($krs->nilai?->status_lulus)
                                            <span class="badge badge-pass">Lulus</span>
                                        @else
                                            <span class="badge badge-fail">Belum Lulus</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="summary">
                        <div>
                            <span>@if($isAllSemesters)IPK Kumulatif@else IPS Semester Terpilih@endif</span>
                            <strong>{{ number_format($ips, 2) }}</strong>
                        </div>
                        <div>
                            <span>IPK Kumulatif</span>
                            <strong>{{ number_format($ipk, 2) }}</strong>
                        </div>
                    </div>
                @endif
            </article>

            <aside class="panel">
                <div class="panel-header">
                    <h2>Catatan Dosen Wali</h2>
                </div>

             <!-- <div class="notes">
                    @forelse ($mahasiswa->catatanDosenWali as $catatan)
                        <article class="note">
                            <time>{{ $catatan->tanggal->format('d M Y') }}</time>
                            <p>{{ $catatan->catatan }}</p>
                            <small>{{ $catatan->dosen->nama }}</small>
                        </article>
                    @empty
                        <div class="empty">Belum ada catatan dosen wali.</div>
                    @endforelse
                </div>  -->
            </aside>
        </section>
    </main>
</body>
</html>
