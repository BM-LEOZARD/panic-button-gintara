<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portal Login - Panic Button Gintara Net</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #e0e5ec 0%, #f0f4f8 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi latar belakang - disesuaikan dengan warna portal */
        body::before {
            content: '';
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
            top: -200px;
            right: -200px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, transparent 70%);
            bottom: -150px;
            left: -100px;
            pointer-events: none;
        }

        /* Card - mengadopsi style dari login.blade.php */
        .card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 24px;
            padding: 44px 40px 36px;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08), 0 8px 20px rgba(0, 0, 0, 0.06);
            animation: slideUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
            position: relative;
            z-index: 1;
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

        /* Header - mengadopsi style dari login.blade.php */
        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-wrap {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .logo-wrap img {
            height: 46px;
            width: auto;
        }

        .header h1 {
            color: #1e293b;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .header p {
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
        }

        /* Badge halaman - mengadopsi style dari login.blade.php */
        .page-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            padding: 5px 14px;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .page-badge i {
            color: #2563eb;
            font-size: 14px;
        }

        .page-badge span {
            color: #2563eb;
        }

        /* Alert - mengadopsi style dari login.blade.php */
        .alert-message {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: none;
            align-items: center;
            gap: 8px;
        }

        .alert-message.show {
            display: flex;
        }

        .alert-message.success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-message.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-message i {
            font-size: 16px;
        }

        /* Laravel session error - mengadopsi style dari login.blade.php */
        .laravel-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .laravel-error i {
            font-size: 16px;
        }

        /* Form - mengadopsi style dari login.blade.php */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        label i {
            color: #2563eb;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 16px;
            pointer-events: none;
            transition: color 0.2s;
            z-index: 1;
        }

        .input-wrapper:focus-within .input-icon {
            color: #2563eb;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 13px 16px 13px 44px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #1e293b;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.25s ease;
            letter-spacing: 0.3px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        input::placeholder {
            color: #94a3b8;
            font-style: italic;
            font-weight: 400;
        }

        /* Style untuk input password dengan toggle */
        input[type="password"].with-toggle,
        input[type="text"].with-toggle {
            padding-right: 45px;
        }

        /* Toggle password button - mengadopsi style dari login.blade.php */
        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            border-radius: 50%;
        }

        .toggle-password:hover {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.1);
        }

        .toggle-password:active {
            transform: scale(0.95);
        }

        .toggle-password i {
            font-size: 18px;
            transition: transform 0.2s;
        }

        .toggle-password:hover i {
            transform: scale(1.1);
        }

        /* Checkbox group - mengadopsi style dari login.blade.php */
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            font-size: 13px;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
            cursor: pointer;
            padding: 0;
        }

        .remember i {
            color: #2563eb;
            font-size: 14px;
        }

        /* Button - mengadopsi style dari login.blade.php */
        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 50px;
            background: #2563eb;
            color: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.35);
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login i {
            font-size: 16px;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0);
            transition: background 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(37, 99, 235, 0.5);
            background: #1d4ed8;
        }

        .btn-login:hover::after {
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* User link - mengadopsi style dari login.blade.php */
        .user-link {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .user-link i {
            color: #2563eb;
        }

        .user-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .user-link a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .user-link a i {
            font-size: 14px;
            transition: transform 0.2s;
        }

        .user-link a:hover i {
            transform: translateX(3px);
        }

        /* Copyright - mengadopsi style dari login.blade.php */
        .copyright {
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .copyright i {
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .card {
                padding: 32px 24px 28px;
            }

            .checkbox-group {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="card">

        <!-- Header - mengadopsi struktur dari login.blade.php -->
        <div class="header">
            <div class="logo-wrap">
                <img src="{{ asset('asset/logo.png') }}" alt="Gintara Net" />
            </div>
            <h1>GINTARA NET</h1>
            <p>Sistem Dashboard Panic Button</p>
            <div class="page-badge">
                <i class="bi bi-shield-fill-check"></i> Portal Login
            </div>
        </div>

        <!-- Laravel Session Error -->
        @if (session('error'))
            <div class="laravel-error">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Laravel Validation Errors -->
        @if ($errors->any())
            <div class="laravel-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Alert JS -->
        <div class="alert-message" id="alertMessage"></div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('portal.login.post') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label for="username">
                    <i class="bi bi-person-fill"></i> Username
                </label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="bi bi-person"></i></span>
                    <input type="text" id="username" name="username" placeholder="Masukkan username"
                        value="{{ old('username') }}" autocomplete="username" required />
                </div>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="bi bi-lock-fill"></i> Password
                </label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password" class="with-toggle"
                        placeholder="Masukkan Password" autocomplete="current-password" required />
                    <button type="button" class="toggle-password" onclick="togglePassword('password', this)"
                        aria-label="Tampilkan password">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>
            </div>

            <div class="checkbox-group">
                <label class="remember">
                    <input type="checkbox" name="remember" id="remember" checked />
                    <i class="bi bi-check-circle-fill"></i> Ingat Saya
                </label>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <i class="bi bi-box-arrow-in-right"></i> Masuk Sistem
            </button>
        </form>

        <!-- Copyright -->
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
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (!username) {
                e.preventDefault();
                showAlert('Username tidak boleh kosong!', 'error');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                showAlert('Password minimal 6 karakter!', 'error');
                return;
            }

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
        });

        function showAlert(message, type) {
            const el = document.getElementById('alertMessage');
            const icon = type === 'error' ? 'bi bi-exclamation-triangle-fill' : 'bi bi-check-circle-fill';
            el.innerHTML = '<i class="' + icon + '"></i> ' + message;
            el.className = 'alert-message show ' + type;

            setTimeout(() => {
                el.classList.remove('show');
            }, 3000);
        }

        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('loginForm').requestSubmit();
            }
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('mouseenter', function() {
                const input = document.getElementById(this.getAttribute('onclick').match(/'([^']+)'/)[1]);
                this.setAttribute('title', input.type === 'password' ? 'Tampilkan password' :
                    'Sembunyikan password');
            });
        });
    </script>
</body>

</html>
