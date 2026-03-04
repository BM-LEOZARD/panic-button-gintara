<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Login - Gintara Net</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            /* Tema Biru Putih (sama dengan dashboard) */
            --primary-blue: #0066cc;
            --secondary-blue: #4d94ff;
            --light-blue: #e6f0ff;
            --soft-blue: #f0f7ff;
            --pure-white: #ffffff;
            --off-white: #f8fafc;
            --dark-blue: #003d99;
            --accent-blue: #1a75ff;
            --text-primary: #1a2634;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --border-light: #e2e8f0;
            --shadow-sm: 0 4px 6px rgba(0, 102, 204, 0.1);
            --shadow-md: 0 8px 20px rgba(0, 102, 204, 0.15);
            --gradient-blue: linear-gradient(135deg, #0066cc, #4d94ff);

            /* Status colors */
            --red: #e63946;
            --green: #2dc653;

            /* Glass effect */
            --glass: rgba(255, 255, 255, 0.07);
            --border: rgba(255, 255, 255, 0.12);
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--soft-blue);
            color: var(--text-primary);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: clamp(12px, 4vw, 24px);
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Background pattern (sama dengan dashboard) */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(circle at 10% 20%, rgba(0, 102, 204, 0.03) 0%, transparent 30%),
                radial-gradient(circle at 90% 80%, rgba(77, 148, 255, 0.03) 0%, transparent 30%);
            pointer-events: none;
        }

        /* ===== CARD ===== */
        .card {
            width: 100%;
            max-width: min(460px, 100%);
            background: var(--pure-white);
            border: 1px solid var(--border-light);
            border-radius: 20px;
            padding: clamp(28px, 6vw, 44px) clamp(20px, 4vw, 40px) clamp(24px, 5vw, 36px);
            box-shadow: var(--shadow-md);
            animation: slideUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
            position: relative;
            z-index: 1;
            margin: auto;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: clamp(20px, 4vh, 32px);
        }

        .logo-wrap {
            width: clamp(60px, 15vw, 72px);
            height: clamp(60px, 15vw, 72px);
            border-radius: 16px;
            background: var(--light-blue);
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto clamp(12px, 2vh, 16px);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            padding: 8px;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header h1 {
            font-family: 'Rajdhani', sans-serif;
            color: var(--text-primary);
            font-size: clamp(18px, 5vw, 22px);
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .header h1 span {
            color: var(--primary-blue);
        }

        .header p {
            color: var(--text-muted);
            font-size: clamp(11px, 3vw, 13px);
            font-weight: 500;
        }

        .page-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--light-blue);
            border: 1px solid var(--border-light);
            border-radius: 50px;
            padding: 5px 14px;
            color: var(--text-secondary);
            font-size: clamp(11px, 2.5vw, 12px);
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .page-badge i {
            color: var(--primary-blue);
            font-size: clamp(12px, 3vw, 14px);
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: clamp(10px, 2vh, 12px) clamp(12px, 3vw, 16px);
            border-radius: 12px;
            font-size: clamp(12px, 3vw, 13px);
            font-weight: 500;
            margin-bottom: clamp(14px, 3vh, 20px);
            display: none;
            align-items: center;
            gap: 8px;
            word-break: break-word;
        }

        .alert.show {
            display: flex;
        }

        .alert.success {
            background: rgba(45, 198, 83, 0.1);
            border: 1px solid rgba(45, 198, 83, 0.3);
            color: var(--green);
        }

        .alert.error {
            background: rgba(230, 57, 70, 0.1);
            border: 1px solid rgba(230, 57, 70, 0.3);
            color: var(--red);
        }

        .alert i {
            font-size: clamp(14px, 3.5vw, 16px);
            flex-shrink: 0;
        }

        .laravel-alert {
            padding: clamp(10px, 2vh, 12px) clamp(12px, 3vw, 16px);
            border-radius: 12px;
            font-size: clamp(12px, 3vw, 13px);
            font-weight: 500;
            margin-bottom: clamp(14px, 3vh, 20px);
            display: flex;
            align-items: flex-start;
            gap: 8px;
            word-break: break-word;
        }

        .laravel-alert.error {
            background: rgba(230, 57, 70, 0.1);
            border: 1px solid rgba(230, 57, 70, 0.3);
            color: var(--red);
        }

        .laravel-alert.success {
            background: rgba(45, 198, 83, 0.1);
            border: 1px solid rgba(45, 198, 83, 0.3);
            color: var(--green);
        }

        .laravel-alert i {
            font-size: clamp(14px, 3.5vw, 16px);
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: clamp(14px, 3vh, 20px);
        }

        label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            font-size: clamp(10px, 2.5vw, 12px);
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        label i {
            color: var(--primary-blue);
            font-size: clamp(12px, 3vw, 14px);
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--text-muted);
            font-size: clamp(14px, 3.5vw, 16px);
            pointer-events: none;
            transition: color 0.2s;
            z-index: 1;
        }

        .input-wrap:focus-within .input-icon {
            color: var(--primary-blue);
        }

        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: clamp(11px, 2.5vh, 13px) 16px clamp(11px, 2.5vh, 13px) 44px;
            background: var(--off-white);
            border: 1px solid var(--border-light);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: clamp(13px, 3.5vw, 14px);
            font-weight: 500;
            transition: all 0.25s ease;
            letter-spacing: 0.3px;
            -webkit-appearance: none;
            appearance: none;
        }

        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: var(--pure-white);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        input::placeholder {
            color: var(--text-muted);
            font-style: italic;
            font-weight: 400;
        }

        /* Toggle password */
        .toggle-pw {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            border-radius: 50%;
            min-width: 36px;
            min-height: 36px;
            -webkit-tap-highlight-color: transparent;
        }

        .toggle-pw:hover {
            color: var(--primary-blue);
            background: var(--light-blue);
        }

        .toggle-pw:active {
            transform: scale(0.95);
        }

        .toggle-pw i {
            font-size: clamp(16px, 4vw, 18px);
            transition: transform 0.2s;
        }

        .toggle-pw:hover i {
            transform: scale(1.1);
        }

        /* Remember me */
        .row-check {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: clamp(20px, 4vh, 28px);
            flex-wrap: wrap;
            gap: 8px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: clamp(12px, 3vw, 13px);
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        .remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary-blue);
            cursor: pointer;
            padding: 0;
            flex-shrink: 0;
        }

        .remember i {
            color: var(--primary-blue);
            font-size: clamp(13px, 3vw, 14px);
        }

        /* Submit button */
        .btn {
            width: 100%;
            padding: clamp(12px, 3vh, 14px);
            border: none;
            border-radius: 50px;
            background: var(--gradient-blue);
            color: var(--pure-white);
            font-family: inherit;
            font-size: clamp(14px, 3.5vw, 15px);
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: var(--shadow-sm);
            margin-bottom: clamp(16px, 3vh, 24px);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 48px;
            -webkit-tap-highlight-color: transparent;
        }

        .btn i {
            font-size: clamp(14px, 3.5vw, 16px);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .copyright {
            text-align: center;
            color: var(--text-muted);
            font-size: clamp(10px, 2.5vw, 11px);
        }

        .copyright i {
            color: var(--primary-blue);
        }

        /* ===== LANDSCAPE ===== */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 12px;
                align-items: flex-start;
            }

            .card {
                padding: 20px 24px;
                margin: 10px auto;
            }

            .logo-wrap {
                width: 48px;
                height: 48px;
                margin-bottom: 8px;
            }

            .header {
                margin-bottom: 16px;
            }

            .form-group {
                margin-bottom: 12px;
            }

            .row-check {
                margin-bottom: 16px;
            }

            .btn {
                margin-bottom: 12px;
            }
        }

        /* ===== TABLET ===== */
        @media (min-width: 768px) and (max-width: 1024px) {
            .card {
                max-width: 480px;
            }
        }
    </style>
</head>

<body>
    <div class="card">

        {{-- Header --}}
        <div class="header">
            <div class="logo-wrap">
                <img src="{{ asset('asset/logo.png') }}" alt="Gintara Net"
                    onerror="this.src='https://via.placeholder.com/56/0066cc/4d94ff?text=GN'; this.onerror=null;" />
            </div>
            <h1>GINTARA NET</h1>
            <p>Sistem Panic Button</p>
            <div class="page-badge">
                <i class="bi bi-shield-lock-fill"></i> Login
            </div>
        </div>

        {{-- Session success --}}
        @if (session('success'))
            <div class="laravel-alert success">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        {{-- Session error --}}
        @if (session('error'))
            <div class="laravel-alert error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="laravel-alert error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Alert JS --}}
        <div class="alert" id="jsAlert"></div>

        {{-- Form Login --}}
        <form method="POST" action="{{ route('login.enduser') }}" id="loginForm">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email">
                    <i class="bi bi-envelope-fill"></i> Email
                </label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="email" name="email" placeholder="Masukkan Email"
                        value="{{ old('email') }}" autocomplete="email" required />
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">
                    <i class="bi bi-shield-lock-fill"></i> Password
                </label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" id="password" name="password" placeholder="Masukkan Password"
                        autocomplete="current-password" required />
                    <button type="button" class="toggle-pw" onclick="togglePassword('password', this)"
                        aria-label="Tampilkan password">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>
            </div>

            {{-- Remember me --}}
            <div class="row-check">
                <label class="remember">
                    <input type="checkbox" name="remember" id="remember" checked />
                    <i class="bi bi-check-circle-fill"></i> Ingat Saya
                </label>
            </div>

            <button type="submit" class="btn" id="submitBtn">
                <i class="bi bi-box-arrow-in-right"></i> Masuk ke Dashboard
            </button>
        </form>

        <div class="copyright">
            <i class="bi bi-c-circle"></i> <span id="copyright-year"></span> Gintara Net. All rights reserved.
            <script>
                document.getElementById('copyright-year').textContent = new Date().getFullYear();
            </script>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const passwordInput = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.className = 'bi bi-eye-slash-fill';
                button.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                passwordInput.type = 'password';
                icon.className = 'bi bi-eye-fill';
                button.setAttribute('aria-label', 'Tampilkan password');
            }
            passwordInput.focus();
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const pass = document.getElementById('password').value;

            if (!email) {
                e.preventDefault();
                showAlert('Email tidak boleh kosong.', 'error');
                return;
            }
            if (!pass || pass.length < 6) {
                e.preventDefault();
                showAlert('Password minimal 6 karakter.', 'error');
                return;
            }

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML =
                '<i class="bi bi-hourglass-split"></i> Memproses...';
        });

        function showAlert(msg, type) {
            const el = document.getElementById('jsAlert');
            const icon = type === 'error' ?
                'bi bi-exclamation-triangle-fill' :
                'bi bi-check-circle-fill';
            el.innerHTML = '<i class="' + icon + '"></i> ' + msg;
            el.className = 'alert show ' + type;
            setTimeout(() => el.classList.remove('show'), 3000);
        }

        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('loginForm').requestSubmit();
            }
        });

        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) event.preventDefault();
            lastTouchEnd = now;
        }, false);
    </script>
</body>

</html>
