<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Animate CSS -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <title>SIPENA Luminara</title>
    <style>
        :root {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            --primary: #38bdf8; /* biru muda */
            --primary-dark: #0ea5e9;
            --ink: #172026;
            --muted: #667085;
            --line: #d8dee4;
            --panel: #ffffff;
            --page: #e0f2fe; /* biru muda sangat terang */
            --danger: #991b1b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            color: var(--ink);
        }

        main {
            width: min(420px, calc(100% - 32px));
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 32px 28px 28px 28px;
            box-shadow: 0 8px 32px 0 rgba(56, 189, 248, 0.10), 0 1.5px 8px 0 rgba(14, 165, 233, 0.08);
            transition: box-shadow 0.2s;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
            color: var(--primary-dark);
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        p {
            margin: 0 0 20px;
            color: var(--muted);
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 700;
        }

        input {
            width: 100%;
            border: 1.5px solid var(--line);
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 15px;
            background: #f0f9ff;
            transition: border 0.2s, box-shadow 0.2s;
        }
        input:focus {
            border: 1.5px solid var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px #bae6fd;
        }

        button {
            width: 100%;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(90deg, var(--primary) 60%, var(--primary-dark) 100%);
            color: #ffffff;
            cursor: pointer;
            font-weight: 700;
            padding: 13px 0;
            font-size: 16px;
            box-shadow: 0 2px 8px 0 rgba(56, 189, 248, 0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        button:hover {
            background: linear-gradient(90deg, var(--primary-dark) 60%, var(--primary) 100%);
            box-shadow: 0 4px 16px 0 rgba(56, 189, 248, 0.18);
        }

        .error {
            margin-bottom: 14px;
            padding: 10px 12px;
            border-radius: 6px;
            color: var(--danger);
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .accounts {
            margin-top: 22px;
            padding-top: 16px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            background: #f0f9ff;
            border-radius: 0 0 12px 12px;
        }
    </style>
</head>
<body>
    <main>
        <h1>Login ERICK </h1>
        
        <p>Masuk sesuai akun mahasiswa atau dosen</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <button type="submit">Masuk</button>
        </form>

        <div class="accounts">
            <strong>Akun Mahasiswa:</strong><br>
            nim@luminara.id / password<br>
            
        </div>
        <div class="accounts"  style="text-align: center;">
            <strong>ERICK (Evaluasi Rekam Indeks, Capaian, dan Konsultasi Akademik)</strong>
           <div class="text-center mt-4">

  <strong>
    Chepy Perdana, S.Kom., M.Pd.<br>
   <a href="https://luminara.web.id" target="_blank" style="color: var(--primary); text-decoration: none;">Luminara - 2026</a> 
  </strong>
  

</div>
            
        </div>
        
    </main>
</body>
</html>
