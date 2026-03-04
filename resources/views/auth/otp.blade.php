<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
    <title>Verifikasi OTP - Gintara Net</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <style>
        :root {
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
            --red: #e63946;
            --green: #2dc653;
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
            max-width: min(480px, 100%);
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

        /* ===== OTP INFO ===== */
        .otp-info {
            text-align: center;
            color: var(--text-secondary);
            font-size: clamp(12px, 3vw, 13px);
            margin-bottom: clamp(20px, 4vh, 28px);
            line-height: 1.6;
            padding: clamp(12px, 2.5vw, 16px);
            background: var(--soft-blue);
            border: 1px solid var(--border-light);
            border-radius: 12px;
        }

        .otp-info strong {
            color: var(--primary-blue);
            font-weight: 700;
        }

        .otp-info i {
            color: var(--primary-blue);
        }

        /* ===== OTP INPUTS ===== */
        .otp-inputs {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: clamp(6px, 1.5vw, 12px);
            justify-content: center;
            align-items: center;
            margin-bottom: clamp(20px, 4vh, 28px);
            width: 100%;
        }

        .otp-box {
            flex: 0 0 auto;
            width: clamp(42px, 12vw, 58px);
            height: clamp(52px, 14vw, 68px);
            background: var(--off-white);
            border: 1px solid var(--border-light);
            border-radius: clamp(10px, 2vw, 14px);
            color: var(--text-primary);
            font-size: clamp(24px, 6vw, 32px);
            font-weight: 700;
            text-align: center;
            font-family: 'DM Sans', monospace;
            transition: all 0.25s ease;
            -webkit-appearance: none;
            appearance: none;
        }

        .otp-box:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: var(--pure-white);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .otp-box::-webkit-outer-spin-button,
        .otp-box::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .otp-box[type=number] {
            -moz-appearance: textfield;
        }

        @media (max-width: 340px) {
            .otp-inputs {
                gap: 4px;
            }

            .otp-box {
                width: 36px;
                height: 46px;
                font-size: 22px;
            }

            .card {
                padding-left: 12px;
                padding-right: 12px;
            }
        }

        /* ===== TIMER ===== */
        .timer {
            text-align: center;
            margin-bottom: clamp(16px, 3vh, 24px);
            color: var(--text-muted);
            font-size: clamp(13px, 3vw, 14px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(6px, 1.5vw, 10px);
            flex-wrap: wrap;
        }

        .timer i {
            color: var(--primary-blue);
            font-size: clamp(14px, 3.5vw, 16px);
        }

        .timer .countdown {
            color: var(--text-primary);
            font-weight: 700;
            background: var(--light-blue);
            border: 1px solid var(--border-light);
            padding: clamp(4px, 1vh, 6px) clamp(8px, 2vw, 12px);
            border-radius: 50px;
            font-size: clamp(14px, 3.5vw, 15px);
            min-width: 70px;
            text-align: center;
        }

        .timer.expired .countdown {
            color: var(--red);
            background: rgba(230, 57, 70, 0.08);
            border-color: rgba(230, 57, 70, 0.25);
        }

        /* ===== RESEND ===== */
        .resend {
            text-align: center;
            margin-bottom: clamp(20px, 4vh, 28px);
        }

        .resend-btn {
            background: none;
            border: 1px solid var(--border-light);
            color: var(--text-muted);
            font-family: inherit;
            font-size: clamp(12px, 3vw, 13px);
            font-weight: 600;
            padding: clamp(8px, 2vh, 10px) clamp(16px, 4vw, 24px);
            border-radius: 50px;
            cursor: not-allowed;
            opacity: 0.5;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
        }

        .resend-btn.active {
            background: var(--light-blue);
            color: var(--primary-blue);
            border-color: var(--secondary-blue);
            cursor: pointer;
            opacity: 1;
        }

        .resend-btn.active:hover {
            background: #d4e8ff;
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .resend-btn.active:active {
            transform: translateY(1px);
        }

        /* ===== SUBMIT BUTTON ===== */
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
            margin-bottom: clamp(12px, 2vh, 16px);
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

        /* ===== BACK LINK ===== */
        .back-link {
            text-align: center;
            margin-bottom: clamp(16px, 3vh, 20px);
        }

        .back-link a {
            color: var(--text-muted);
            font-size: clamp(12px, 3vw, 13px);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: color 0.2s;
            padding: 8px 12px;
            min-height: 44px;
            border-radius: 50px;
        }

        .back-link a:hover {
            color: var(--primary-blue);
            background: var(--light-blue);
        }

        .back-link i {
            font-size: clamp(13px, 3.2vw, 14px);
        }

        /* ===== COPYRIGHT ===== */
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

            .otp-info {
                margin-bottom: 14px;
            }

            .otp-inputs {
                margin-bottom: 14px;
            }

            .timer {
                margin-bottom: 12px;
            }

            .resend {
                margin-bottom: 14px;
            }

            .btn {
                margin-bottom: 10px;
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
                <i class="bi bi-shield-check"></i> Verifikasi OTP
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

        {{-- Info OTP --}}
        <div class="otp-info">
            <i class="bi bi-shield-lock-fill" style="margin-right: 4px;"></i>
            Kode OTP 6 digit telah dikirim ke email <strong>{{ $maskedEmail }}</strong>.<br>
            Masukkan kode di bawah ini untuk menyelesaikan login.
        </div>

        {{-- Form OTP --}}
        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
            @csrf

            <input type="hidden" name="otp" id="otpHidden" />

            <div class="otp-inputs">
                <input type="text" class="otp-box" id="otp1" maxlength="1" inputmode="numeric"
                    autocomplete="off" placeholder="0" aria-label="Digit 1">
                <input type="text" class="otp-box" id="otp2" maxlength="1" inputmode="numeric"
                    autocomplete="off" placeholder="0" aria-label="Digit 2">
                <input type="text" class="otp-box" id="otp3" maxlength="1" inputmode="numeric"
                    autocomplete="off" placeholder="0" aria-label="Digit 3">
                <input type="text" class="otp-box" id="otp4" maxlength="1" inputmode="numeric"
                    autocomplete="off" placeholder="0" aria-label="Digit 4">
                <input type="text" class="otp-box" id="otp5" maxlength="1" inputmode="numeric"
                    autocomplete="off" placeholder="0" aria-label="Digit 5">
                <input type="text" class="otp-box" id="otp6" maxlength="1" inputmode="numeric"
                    autocomplete="off" placeholder="0" aria-label="Digit 6">
            </div>

            {{-- Timer --}}
            <div class="timer" id="timerEl">
                <i class="bi bi-hourglass-split"></i>
                <span>Kode berlaku:</span>
                <span class="countdown" id="countdown" aria-live="polite">03:00</span>
            </div>

            {{-- Resend --}}
            <div class="resend">
                <button type="button" class="resend-btn" id="resendBtn" disabled aria-label="Kirim ulang OTP">
                    <i class="bi bi-arrow-repeat"></i> Kirim Ulang Kode
                </button>
            </div>

            <button type="submit" class="btn" id="submitBtn">
                <i class="bi bi-check2-circle"></i> Verifikasi & Masuk
            </button>
        </form>

        {{-- Form Resend --}}
        <form method="POST" action="{{ route('otp.resend') }}" id="resendForm" style="display:none;">
            @csrf
        </form>

        {{-- Kembali ke login --}}
        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>

        <div class="copyright">
            <i class="bi bi-c-circle"></i> <span id="copyright-year"></span> Gintara Net. All rights reserved.
            <script>
                document.getElementById('copyright-year').textContent = new Date().getFullYear();
            </script>
        </div>
    </div>

    <script>
        const otpInputs = document.querySelectorAll('.otp-box');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 1);
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                for (let i = 0; i < Math.min(paste.length, otpInputs.length); i++) {
                    otpInputs[i].value = paste[i];
                }
                otpInputs[Math.min(paste.length, otpInputs.length - 1)].focus();
            });
        });

        let timeLeft = 180;
        const countdownEl = document.getElementById('countdown');
        const timerEl = document.getElementById('timerEl');
        const resendBtn = document.getElementById('resendBtn');
        let timerInterval;

        function updateTimer() {
            const m = Math.floor(timeLeft / 60);
            const s = timeLeft % 60;
            countdownEl.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerEl.classList.add('expired');
                countdownEl.textContent = '00:00';
                resendBtn.disabled = false;
                resendBtn.classList.add('active');
            }
        }

        function startTimer() {
            if (timerInterval) clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimer();
            }, 1000);
        }

        startTimer();
        updateTimer();

        resendBtn.addEventListener('click', function() {
            if (!this.disabled) {
                document.getElementById('resendForm').submit();
            }
        });

        document.getElementById('otpForm').addEventListener('submit', function(e) {
            let otpValue = '';
            let complete = true;

            otpInputs.forEach(input => {
                if (!input.value) complete = false;
                otpValue += input.value;
            });

            if (!complete || otpValue.length !== 6) {
                e.preventDefault();
                alert('Harap isi semua 6 digit kode OTP.');
                return;
            }

            document.getElementById('otpHidden').value = otpValue;
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML =
                '<i class="bi bi-hourglass-split"></i> Memverifikasi...';
        });

        window.addEventListener('load', function() {
            otpInputs[0].focus();
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
