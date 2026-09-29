<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
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
            width: min(880px, calc(100% - 32px));
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

        input,
        select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px 11px;
            background: #ffffff;
        }

        .hint {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 13px;
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

        @media (max-width: 760px) {
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
                <h1>Edit Mahasiswa</h1>
                <p>Perbarui data mahasiswa dan akun login yang terhubung.</p>
            </div>
            <a href="{{ route('pengelola.mahasiswa') }}">Kembali</a>
        </section>

        @if ($errors->any())
            <div class="notice">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="panel">
            <form method="POST" action="{{ route('pengelola.mahasiswa.update', $mahasiswa) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div>
                        <label for="nim">NIM</label>
                        <input id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required>
                    </div>

                    <div>
                        <label for="nama">Nama</label>
                        <input id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required>
                    </div>

                    <div>
                        <label for="program_studi">Program Studi</label>
                        <input id="program_studi" name="program_studi" value="{{ old('program_studi', $mahasiswa->program_studi) }}" required>
                    </div>

                    <div>
                        <label for="angkatan">Angkatan</label>
                        <input id="angkatan" type="number" name="angkatan" min="2000" max="2100" value="{{ old('angkatan', $mahasiswa->angkatan) }}" required>
                    </div>

                    <div>
                        <label for="dosen_wali_id">Dosen Wali</label>
                        <select id="dosen_wali_id" name="dosen_wali_id" required>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" @selected(old('dosen_wali_id', $mahasiswa->dosen_wali_id) == $dosen->id)>
                                    {{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="email">Email Login</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $userMahasiswa?->email) }}">
                        <span class="hint">Kosongkan untuk otomatis: NIM@student.local</span>
                    </div>

                    <div class="full">
                        <label for="foto">Foto Mahasiswa</label>
                        @if ($mahasiswa->foto)
                            <div style="margin-bottom: 12px;">
                                <img src="{{ asset('uploads/' . $mahasiswa->foto) }}" alt="{{ $mahasiswa->nama }}" 
                                     style="max-width: 150px; max-height: 150px; border-radius: 6px; object-fit: cover;">
                            </div>
                        @endif
                        <input id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/jpg">
                        <span class="hint">Format: JPEG, PNG, JPG | Ukuran maksimal: 5 MB</span>
                    </div>

                    <div>
                        <label for="password">Password Baru</label>
                        <input id="password" type="text" name="password">
                        <span class="hint">Kosongkan bila password tidak diubah.</span>
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
