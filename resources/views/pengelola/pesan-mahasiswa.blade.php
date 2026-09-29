<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Mahasiswa ke Dosen Wali</title>
    <style>
        :root {
            font-family: Arial, Helvetica, sans-serif;
            --primary: #155e75;
            --ink: #172026;
            --muted: #667085;
            --line: #d8dee4;
            --panel: #ffffff;
            --page: #f5f7f8;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--page);
            color: var(--ink);
        }

        .shell {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 40px;
        }

        h1 { margin: 0 0 8px; font-size: 28px; }
        h2 { margin: 0 0 12px; font-size: 20px; }
        p { margin: 0; color: var(--muted); }
        a { color: var(--primary); text-decoration: none; font-weight: 700; }

        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 20px;
            align-items: flex-start;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(17, 24, 39, 0.04);
            padding: 18px;
            margin-bottom: 18px;
        }

        .filter-row {
            display: flex;
            gap: 14px;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
        }

        select {
            width: 100%;
            max-width: 320px;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px 12px;
            background: #ffffff;
        }

        .pesan-list {
            display: grid;
            gap: 14px;
        }

        .pesan-item {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(17, 24, 39, 0.04);
        }

        .pesan-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .pesan-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: 13px;
        }

        .pesan-title {
            font-weight: 700;
            color: var(--ink);
        }

        .pesan-body {
            margin: 0;
            line-height: 1.6;
        }

        .balasan-body {
            margin-top: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        .balasan-body strong {
            display: block;
            margin-bottom: 6px;
            color: #0369a1;
        }

        .balasan-body p {
            margin: 0 0 8px;
            line-height: 1.6;
        }

        .balasan-body small {
            color: var(--muted);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
            background: #e0f2fe;
            color: #0369a1;
        }

        .empty {
            color: var(--muted);
            padding: 40px 20px;
            text-align: center;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 6px;
            background: #e2e8f0;
            color: var(--ink);
        }

        @media (max-width: 760px) {
            .topbar {
                flex-direction: column;
            }

            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="topbar">
            <div>
                <h1>Pesan Mahasiswa ke Dosen Wali</h1>
                <p>Melihat semua pesan dari mahasiswa dan filter berdasarkan dosen wali.</p>
            </div>
            <div class="actions">
                <a href="{{ route('home') }}">Beranda</a>
            </div>
        </section>

        <section class="panel">
            <form method="GET" class="filter-row">
                <div>
                    <label for="dosen_id">Filter Dosen Wali</label>
                    <select id="dosen_id" name="dosen_id" onchange="this.form.submit()">
                        <option value="">Semua Dosen Wali</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected((string) $dosenId === (string) $dosen->id)>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if ($pesan->count() > 0)
                <div class="pesan-list">
                    @foreach ($pesan as $msg)
                        <div class="pesan-item">
                            <div class="pesan-header">
                                <div>
                                    <div class="pesan-title">{{ $msg->mahasiswa->nim }} - {{ $msg->mahasiswa->nama }}</div>
                                    <div class="pesan-meta">
                                        <span>Dosen Wali: {{ $msg->mahasiswa->dosenWali?->nama ?? '-' }}</span>
                                        <span class="badge">{{ $msg->tipe }}</span>
                                        <span>{{ $msg->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="pesan-body">{{ $msg->pesan }}</p>
                            @if ($msg->balasan_dosen)
                                <div class="balasan-body">
                                    <strong>Balasan Dosen Wali</strong>
                                    <p>{{ $msg->balasan_dosen }}</p>
                                    <small>
                                        {{ $msg->dosen?->nama ?? 'Dosen Wali' }}
                                        @if ($msg->dibalas_at)
                                            - {{ $msg->dibalas_at->format('d M Y H:i') }}
                                        @endif
                                    </small>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty">Belum ada pesan mahasiswa untuk filter ini.</div>
            @endif
        </section>
    </main>
</body>
</html>
