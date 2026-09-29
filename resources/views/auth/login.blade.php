<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-RICK System by Luminara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            --primary: #06b6d4; /* Cyan */
            --secondary: #14b8a6; /* Tosca */
            --accent: #0f172a; /* Dark Accent */
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --danger: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--accent);
            color: var(--text-main);
            overflow: hidden;
            position: relative;
        }

        /* --- ANTIGRAVITY ORBS BACKGROUND --- */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 20s infinite ease-in-out alternate;
            z-index: 0;
        }
        
        .orb-1 {
            width: 400px;
            height: 400px;
            background: var(--primary);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 350px;
            height: 350px;
            background: var(--secondary);
            bottom: -50px;
            right: -100px;
            animation-duration: 25s;
            animation-delay: -5s;
        }

        .orb-3 {
            width: 250px;
            height: 250px;
            background: #3b82f6; /* Blueish to add depth */
            bottom: 20%;
            left: 20%;
            animation-duration: 30s;
            animation-delay: -10s;
            opacity: 0.3;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(100px, 50px) scale(1.1); }
            100% { transform: translate(-50px, 100px) scale(0.9); }
        }

        /* --- GLASSMORPHISM LOGIN CARD --- */
        main {
            position: relative;
            z-index: 10;
            width: min(420px, calc(100% - 32px));
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 28px;
            color: white;
            box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
            position: relative;
        }

        .brand-logo::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            box-shadow: inset 0 2px 4px rgba(255,255,255,0.4);
        }

        h1 {
            text-align: center;
            margin: 0 0 8px;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 0.5px;
            background: linear-gradient(to right, #ffffff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p.subtitle {
            text-align: center;
            margin: 0 0 32px;
            color: var(--text-muted);
            font-size: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #cbd5e1;
            letter-spacing: 0.3px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 15px;
            color: white;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: rgba(255,255,255,0.2);
        }

        input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
            background: rgba(15, 23, 42, 0.8);
        }

        .btn-toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            background: transparent !important;
            border: none;
            box-shadow: none !important;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .btn-toggle-password:hover {
            color: white;
        }

        button[type="submit"] {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #ffffff;
            cursor: pointer;
            font-weight: 700;
            padding: 14px 0;
            font-size: 16px;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(6, 182, 212, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .error {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: 10px;
            color: #fca5a5;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .accounts {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.6;
            text-align: center;
        }

        .accounts strong {
            color: #e2e8f0;
            font-weight: 600;
        }

        .accounts a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .accounts a:hover {
            color: #67e8f9;
        }
    </style>
</head>
<body>
    <!-- Antigravity Background Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <main>
        <div class="brand-logo">E</div>
        <h1>Login ERICK</h1>
        <p class="subtitle">Masuk sesuai akun mahasiswa atau dosen</p>

        @if ($errors->any())
            <div class="error">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@luminara.id">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <button type="button" class="btn-toggle-password" id="togglePassword" title="Lihat password">
                        <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                        <svg id="eyeIcon" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit">Masuk</button>
        </form>

        <div class="accounts">
            <strong>Akun Mahasiswa:</strong><br>
            nim@luminara.id / password
        </div>
        <div class="accounts" style="margin-top: 16px; padding-top: 16px;">
            <strong style="display:block; margin-bottom:4px; font-size:14px; color:white;">ERICK</strong>
            Evaluasi Rekam Indeks, Capaian, dan Konsultasi Akademik<br><br>
            <strong>Chepy Perdana, S.Kom., M.Pd.</strong><br>
            <a href="https://luminara.web.id" target="_blank">Luminara - 2026</a>
        </div>
    </main>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');
        const eyeSlashIcon = document.querySelector('#eyeSlashIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeSlashIcon.style.display = 'none';
                eyeIcon.style.display = 'block';
                togglePassword.setAttribute('title', 'Sembunyikan password');
            } else {
                eyeSlashIcon.style.display = 'block';
                eyeIcon.style.display = 'none';
                togglePassword.setAttribute('title', 'Lihat password');
            }
        });
    </script>
</body>
</html>