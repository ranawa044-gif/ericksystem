<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mata Kuliah</title>
    <style>
        :root {
            font-family: Arial, Helvetica, sans-serif;
            --primary: #155e75;
            --ink: #172026;
            --muted: #667085;
            --line: #d8dee4;
            --panel: #ffffff;
            --page: #f5f7f8;
            --danger: #991b1b;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--page);
            color: var(--ink);
        }

        .shell {
            width: min(760px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 40px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        p {
            margin: 0;
            color: var(--muted);
        }

        a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .panel,
        .notice {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(17, 24, 39, 0.04);
        }

        .panel {
            padding: 18px;
        }

        .notice {
            padding: 12px 14px;
            margin-bottom: 16px;
            color: var(--danger);
            border-color: #fecaca;
            background: #fef2f2;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 700;
        }

        input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px 11px;
            background: #ffffff;
        }

        .full { grid-column: 1 / -1; }

        .submit {
            border: 0;
            border-radius: 6px;
            background: var(--primary);
            color: #ffffff;
            cursor: pointer;
            font-weight: 700;
            padding: 11px 14px;
        }

        @media (max-width: 680px) {
            .topbar,
            .form-grid {
                grid-template-columns: 1fr;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="topbar">
            <div>
                <h1>Edit Mata Kuliah</h1>
                <p>Perbarui kode, nama, SKS, dan semester mata kuliah.</p>
            </div>
            <a href="{{ route('pengelola.mata-kuliah') }}">Kembali</a>
        </section>

        @if ($errors->any())
            <div class="notice">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="panel">
            <form method="POST" action="{{ route('pengelola.mata-kuliah.update', $mataKuliah) }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div>
                        <label for="kode">Kode</label>
                        <input id="kode" name="kode" value="{{ old('kode', $mataKuliah->kode) }}" required>
                    </div>

                    <div>
                        <label for="nama">Nama Mata Kuliah</label>
                        <input id="nama" name="nama" value="{{ old('nama', $mataKuliah->nama) }}" required>
                    </div>

                    <div>
                        <label for="sks">SKS</label>
                        <input id="sks" type="number" name="sks" min="1" max="6" value="{{ old('sks', $mataKuliah->sks) }}" required>
                    </div>

                    <div>
                        <label for="semester_default">Semester</label>
                        <input id="semester_default" type="number" name="semester_default" min="1" max="14" value="{{ old('semester_default', $mataKuliah->semester_default) }}" required>
                    </div>

                    <div class="full">
                        <button class="submit" type="submit">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
