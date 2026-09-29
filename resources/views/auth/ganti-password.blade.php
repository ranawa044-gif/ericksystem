<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password & Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #155e75;
        }

        body {
            background: linear-gradient(135deg, #f5f7f8 0%, #e8ecf0 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            max-width: 1000px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #667085;
            margin: 0;
        }

        .form-card {
            background: white;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            padding: 28px;
            margin-bottom: 24px;
        }

        .form-card h2 {
            color: var(--primary);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8ecf0;
        }

        .form-group label {
            font-weight: 600;
            color: #172026;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #d8dee4;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(21, 94, 117, 0.1);
        }

        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #0f3f50;
            border-color: #0f3f50;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(21, 94, 117, 0.3);
        }

        .btn-outline-secondary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-secondary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .upload-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #d8dee4;
            margin-bottom: 15px;
            display: block;
        }

        .hint {
            display: block;
            color: #667085;
            font-size: 13px;
            margin-top: 6px;
        }

        .alert {
            border-radius: 6px;
            border: none;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #14532d;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
        }

        .back-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            gap: 10px;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }

            .form-card h2 {
                font-size: 18px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Kelola Akun</h1>
            <p>Ubah password dan foto profil Anda</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Form Ganti Password -->
            <div class="col-lg-6">
                <div class="form-card">
                    <h2>🔐 Ganti Password</h2>
                    <form method="POST" action="{{ route('mahasiswa.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="password_lama" class="form-label">Password Lama</label>
                            <input id="password_lama" type="password" name="password_lama" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password_baru" class="form-label">Password Baru</label>
                            <input id="password_baru" type="password" name="password_baru" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_baru_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input id="password_baru_confirmation" type="password" name="password_baru_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Password</button>
                    </form>
                </div>
            </div>

            <!-- Form Ganti Foto -->
            <div class="col-lg-6">
                <div class="form-card">
                    <h2>📸 Ganti Foto Profil</h2>
                    <form method="POST" action="{{ route('mahasiswa.foto.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if (auth()->user()->mahasiswa && auth()->user()->mahasiswa->foto)
                            <img src="{{ asset('uploads/' . auth()->user()->mahasiswa->foto) }}" alt="Profil" class="upload-preview">
                        @else
                            <div style="width: 120px; height: 120px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; color: #999;">
                                <span>Belum ada foto</span>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="foto" class="form-label">Pilih Foto Profil</label>
                            <input id="foto" type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                            <span class="hint">Format: JPEG, PNG, JPG | Ukuran maksimal: 5 MB</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Foto</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('dashboard.mahasiswa', ['nim' => auth()->user()->mahasiswa->nim]) }}" class="back-link">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
