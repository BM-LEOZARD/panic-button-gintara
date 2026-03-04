<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
    <title>Verifikasi OTP - Gintara Net</title>
    <link rel="icon" type="image/png" href="https://via.placeholder.com/32/0b3d5f/4aa3ff?text=GN" />
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

        :root {
            --blue-dark: #0b3d5f;
            --blue-mid: #1b6b8f;
            --blue-light: #4aa3ff;
            --blue-glow: rgba(74, 163, 255, 0.25);
            --white: #ffffff;
            --white-60: rgba(255, 255, 255, 0.6);
            --white-30: rgba(255, 255, 255, 0.3);
            --white-10: rgba(255, 255, 255, 0.1);
            --white-08: rgba(255, 255, 255, 0.08);
            --error: #ff6b6b;
            --error-bg: rgba(255, 107, 107, 0.15);
            --success: #6bffb8;
            --success-bg: rgba(107, 255, 184, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
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

        /* Dekorasi latar belakang yang responsif */
        body::before {
            content: '';
            position: fixed;
            width: min(600px, 80vw);
            height: min(600px, 80vw);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(74, 163, 255, 0.12) 0%, transparent 70%);
            top: -20%;
            right: -20%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            width: min(400px, 60vw);
            height: min(400px, 60vw);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(27, 107, 143, 0.2) 0%, transparent 70%);
            bottom: -15%;
            left: -15%;
            pointer-events: none;
        }

        /* Card dengan padding responsif */
        .card {
            width: 100%;
            max-width: min(500px, 100%);
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: clamp(20px, 5vw, 28px);
            padding: clamp(28px, 6vw, 44px) clamp(20px, 4vw, 40px) clamp(28px, 5vw, 36px);
            border: 1px solid var(--white-30);
            box-shadow: 0 32px 80px rgba(0, 20, 40, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.15);
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

        /* Header dengan ukuran responsif */
        .header {
            text-align: center;
            margin-bottom: clamp(20px, 4vh, 32px);
        }

        .logo-wrap {
            width: clamp(64px, 15vw, 80px);
            height: clamp(64px, 15vw, 80px);
            border-radius: clamp(16px, 4vw, 20px);
            background: var(--white-10);
            border: 1px solid var(--white-30);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto clamp(12px, 2vh, 16px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            padding: 8px;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header h1 {
            color: var(--white);
            font-size: clamp(18px, 5vw, 24px);
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .header p {
            color: var(--white-60);
            font-size: clamp(11px, 3vw, 13px);
            font-weight: 500;
        }

        /* Badge halaman */
        .page-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--white-10);
            border: 1px solid var(--white-30);
            border-radius: 50px;
            padding: 5px 14px;
            color: var(--white-60);
            font-size: clamp(11px, 2.5vw, 12px);
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .page-badge i {
            color: var(--blue-light);
            font-size: clamp(12px, 3vw, 14px);
        }

        /* Alert dengan ukuran responsif */
        .alert {
            padding: clamp(10px, 2vh, 14px) clamp(12px, 3vw, 16px);
            border-radius: clamp(10px, 2vw, 12px);
            font-size: clamp(12px, 3vw, 13px);
            font-weight: 500;
            margin-bottom: clamp(16px, 3vh, 24px);
            display: none;
            align-items: center;
            gap: 8px;
            word-break: break-word;
        }

        .alert.show {
            display: flex;
        }

        .alert.success {
            background: var(--success-bg);
            border: 1px solid rgba(107, 255, 184, 0.3);
            color: var(--success);
        }

        .alert.error {
            background: var(--error-bg);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: var(--error);
        }

        .alert i {
            font-size: clamp(14px, 3.5vw, 16px);
            flex-shrink: 0;
        }

        /* Info OTP dengan teks responsif */
        .otp-info {
            text-align: center;
            color: var(--white-60);
            font-size: clamp(12px, 3vw, 13px);
            margin-bottom: clamp(20px, 4vh, 28px);
            line-height: 1.5;
            padding: 0 5px;
        }

        .otp-info strong {
            color: var(--blue-light);
            font-weight: 700;
        }

        /* ===== FORM OTP - SELALU 1 BARIS ===== */
        .otp-inputs {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            /* PENTING: Mencegah wrap ke baris baru */
            gap: clamp(6px, 1.5vw, 12px);
            justify-content: center;
            align-items: center;
            margin-bottom: clamp(20px, 4vh, 28px);
            width: 100%;
        }

        .otp-box {
            flex: 0 0 auto;
            /* Mencegah flex item mengecil/membesar */
            width: clamp(42px, 12vw, 58px);
            /* Ukuran proporsional tapi tetap dalam 1 baris */
            height: clamp(52px, 14vw, 68px);
            background: var(--white-08);
            border: 1px solid var(--white-30);
            border-radius: clamp(10px, 2vw, 14px);
            color: var(--white);
            font-size: clamp(24px, 6vw, 32px);
            font-weight: 700;
            text-align: center;
            font-family: 'Plus Jakarta Sans', monospace;
            transition: all 0.25s ease;
            -webkit-appearance: none;
            appearance: none;
        }

        /* Perangkat dengan layar sangat kecil (<= 320px) */
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

        /* Perangkat dengan layar 341px - 375px */
        @media (min-width: 341px) and (max-width: 375px) {
            .otp-box {
                width: 42px;
                height: 52px;
                font-size: 24px;
            }
        }

        /* Touch-friendly improvements untuk mobile */
        @media (hover: none) and (pointer: coarse) {
            .otp-box {
                font-size: clamp(24px, 6vw, 32px);
            }
        }

        .otp-box:focus {
            outline: none;
            border-color: var(--blue-light);
            background: rgba(74, 163, 255, 0.08);
            box-shadow: 0 0 0 3px var(--blue-glow);
        }

        .otp-box::-webkit-outer-spin-button,
        .otp-box::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .otp-box[type=number] {
            -moz-appearance: textfield;
        }

        /* Timer responsif */
        .timer {
            text-align: center;
            margin-bottom: clamp(20px, 4vh, 28px);
            color: var(--white-60);
            font-size: clamp(13px, 3vw, 14px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(6px, 1.5vw, 10px);
            flex-wrap: wrap;
        }

        .timer i {
            color: var(--blue-light);
            font-size: clamp(14px, 3.5vw, 16px);
        }

        .timer .countdown {
            color: var(--white);
            font-weight: 700;
            background: var(--white-10);
            padding: clamp(4px, 1vh, 6px) clamp(8px, 2vw, 12px);
            border-radius: 50px;
            font-size: clamp(14px, 3.5vw, 16px);
            min-width: 70px;
            text-align: center;
        }

        .timer.expired .countdown {
            color: var(--error);
        }

        /* Resend button yang lebih touch-friendly */
        .resend {
            text-align: center;
            margin-bottom: clamp(20px, 4vh, 28px);
        }

        .resend-btn {
            background: none;
            border: 1px solid var(--white-30);
            color: var(--white-60);
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
            -webkit-tap-highlight-color: transparent;
        }

        .resend-btn.active {
            background: var(--white-10);
            color: var(--blue-light);
            border-color: var(--blue-light);
            cursor: pointer;
            opacity: 1;
        }

        .resend-btn.active:hover {
            background: rgba(74, 163, 255, 0.15);
            transform: translateY(-1px);
        }

        .resend-btn.active:active {
            transform: translateY(1px);
        }

        .resend-btn i {
            font-size: clamp(13px, 3.2vw, 14px);
        }

        /* Button utama dengan touch target yang baik */
        .btn {
            width: 100%;
            padding: clamp(12px, 3vh, 16px);
            border: none;
            border-radius: 50px;
            background: var(--blue-light);
            color: var(--white);
            font-family: inherit;
            font-size: clamp(14px, 3.5vw, 15px);
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(74, 163, 255, 0.35);
            margin-bottom: clamp(12px, 2vh, 16px);
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

        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0);
            transition: background 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(74, 163, 255, 0.5);
        }

        .btn:hover::after {
            background: rgba(255, 255, 255, 0.06);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Back link dengan touch target yang baik */
        .back-link {
            text-align: center;
            margin-bottom: clamp(16px, 3vh, 20px);
        }

        .back-link a {
            color: var(--white-60);
            font-size: clamp(12px, 3vw, 13px);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: color 0.2s;
            cursor: pointer;
            padding: 8px 12px;
            min-height: 44px;
            -webkit-tap-highlight-color: transparent;
        }

        .back-link a:hover {
            color: var(--blue-light);
        }

        .back-link a:active {
            opacity: 0.8;
        }

        .back-link i {
            font-size: clamp(13px, 3.2vw, 14px);
        }

        /* Copyright */
        .copyright {
            text-align: center;
            color: rgba(255, 255, 255, 0.25);
            font-size: clamp(10px, 2.5vw, 11px);
        }

        /* Landscape mode optimization */
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

            .otp-inputs {
                margin-bottom: 16px;
            }

            .otp-box {
                width: 45px;
                height: 52px;
                font-size: 24px;
            }

            .timer,
            .resend {
                margin-bottom: 16px;
            }
        }

        /* Tablet optimization - tetap 1 baris */
        @media (min-width: 768px) and (max-width: 1024px) {
            .card {
                max-width: 520px;
            }

            .otp-box {
                width: 65px;
                height: 75px;
                font-size: 34px;
            }
        }

        /* Large desktop */
        @media (min-width: 1200px) {
            .card {
                max-width: 500px;
            }

            .otp-box {
                width: 60px;
                height: 72px;
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        {{-- Header dengan Logo --}}
        <div class="header">
            <div class="logo-wrap">
                <img src="{{ asset('asset/logo.png') }}" alt="Gintara Net Logo"
                    onerror="this.src='https://via.placeholder.com/56/0b3d5f/4aa3ff?text=GN'; this.onerror=null;" />
            </div>
            <h1>GINTARA NET</h1>
            <p>Sistem Panic Button</p>
            <div class="page-badge">
                <i class="bi bi-shield-check"></i> Verifikasi OTP
            </div>
        </div>

        {{-- Alert JS --}}
        <div class="alert" id="jsAlert"></div>

        {{-- Info OTP --}}
        <div class="otp-info">
            <i class="bi bi-shield-lock-fill" style="color: var(--blue-light); margin-right: 4px;"></i>
            Masukkan kode 6 digit yang telah dikirim ke <strong>Email</strong> Anda
        </div>

        {{-- Form OTP - SELALU 1 BARIS --}}
        <form id="otpForm">
            {{-- OTP Input Fields - Selalu dalam satu baris --}}
            <div class="otp-inputs">
                <input type="text" class="otp-box" id="otp1" maxlength="1" pattern="[0-9]" inputmode="numeric"
                    autocomplete="off" placeholder="0" required aria-label="Digit 1 kode OTP">
                <input type="text" class="otp-box" id="otp2" maxlength="1" pattern="[0-9]" inputmode="numeric"
                    autocomplete="off" placeholder="0" required aria-label="Digit 2 kode OTP">
                <input type="text" class="otp-box" id="otp3" maxlength="1" pattern="[0-9]" inputmode="numeric"
                    autocomplete="off" placeholder="0" required aria-label="Digit 3 kode OTP">
                <input type="text" class="otp-box" id="otp4" maxlength="1" pattern="[0-9]" inputmode="numeric"
                    autocomplete="off" placeholder="0" required aria-label="Digit 4 kode OTP">
                <input type="text" class="otp-box" id="otp5" maxlength="1" pattern="[0-9]" inputmode="numeric"
                    autocomplete="off" placeholder="0" required aria-label="Digit 5 kode OTP">
                <input type="text" class="otp-box" id="otp6" maxlength="1" pattern="[0-9]" inputmode="numeric"
                    autocomplete="off" placeholder="0" required aria-label="Digit 6 kode OTP">
            </div>

            {{-- Timer --}}
            <div class="timer" id="timer">
                <i class="bi bi-hourglass-split"></i>
                <span>Kode berlaku:</span>
                <span class="countdown" id="countdown" aria-live="polite">03:00</span>
            </div>

            {{-- Resend OTP --}}
            <div class="resend">
                <button type="button" class="resend-btn" id="resendBtn" disabled aria-label="Kirim ulang kode OTP">
                    <i class="bi bi-arrow-repeat"></i> Kirim Ulang Kode
                </button>
            </div>

            <button type="submit" class="btn" id="submitBtn" aria-label="Verifikasi kode OTP">
                <i class="bi bi-check2-circle"></i> Verifikasi & Masuk
            </button>
        </form>

        {{-- Kembali ke login --}}
        <div class="back-link">
            <a id="backToLogin" role="button" tabindex="0" aria-label="Kembali ke halaman login">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>

        <div class="copyright">
            <i class="bi bi-c-circle"></i> 2026 Gintara Corp. All rights reserved.
        </div>
    </div>

    <script>
        // ========== OTP INPUT HANDLING ==========
        const otpInputs = document.querySelectorAll('.otp-box');

        otpInputs.forEach((input, index) => {
            // Focus next input on typing
            input.addEventListener('input', function(e) {
                // Hanya angka yang diperbolehkan
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 1);

                if (this.value.length === 1) {
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    } else {
                        // Jika input terakhir, bisa langsung submit atau blur
                        this.blur();
                    }
                }
            });

            // Handle backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace') {
                    if (this.value.length === 0 && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                }
            });

            // Handle paste
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');

                if (pasteData.length > 0) {
                    // Split paste data ke input yang tersedia
                    for (let i = 0; i < Math.min(pasteData.length, otpInputs.length); i++) {
                        otpInputs[i].value = pasteData[i];
                    }

                    // Focus ke input setelah paste terakhir atau akhir dari paste
                    const nextIndex = Math.min(pasteData.length, otpInputs.length - 1);
                    otpInputs[nextIndex].focus();
                }
            });

            // Handle keyup untuk memastikan input hanya angka
            input.addEventListener('keyup', function(e) {
                if (this.value.length > 0) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                }
            });
        });

        // ========== TIMER COUNTDOWN ==========
        let timeLeft = 180; // 3 minutes in seconds
        const countdownEl = document.getElementById('countdown');
        const timerEl = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');
        let timerInterval;

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerEl.classList.add('expired');
                countdownEl.textContent = '00:00';
                resendBtn.disabled = false;
                resendBtn.classList.add('active');
                showAlert('Kode OTP telah kedaluwarsa. Silakan kirim ulang.', 'error');
            }
        }

        function startTimer() {
            // Clear existing interval if any
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimer();
            }, 1000);
        }

        // Start timer initially
        startTimer();
        updateTimer();

        // ========== RESEND OTP ==========
        resendBtn.addEventListener('click', function() {
            if (!this.disabled) {
                // Reset timer
                timeLeft = 180;
                timerEl.classList.remove('expired');
                this.disabled = true;
                this.classList.remove('active');

                // Clear OTP inputs
                otpInputs.forEach(input => input.value = '');
                otpInputs[0].focus();

                // Restart timer
                startTimer();
                updateTimer();

                // Show success message
                showAlert('Kode OTP baru telah dikirim ke email Anda (demo)', 'success');
            }
        });

        // ========== FORM SUBMIT ==========
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit ke server untuk demo

            // Kumpulkan semua digit OTP
            let otpComplete = true;
            let otpValue = '';

            otpInputs.forEach(input => {
                if (!input.value) {
                    otpComplete = false;
                }
                otpValue += input.value;
            });

            // Validasi
            if (!otpComplete) {
                showAlert('Harap isi semua digit kode OTP.', 'error');
                return;
            }

            if (otpValue.length !== 6) {
                showAlert('Kode OTP harus 6 digit.', 'error');
                return;
            }

            // Demo validasi - cek apakah kode 123456
            if (otpValue === '123456') {
                // Disable button
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerHTML =
                    '<i class="bi bi-hourglass-split"></i> Memverifikasi...';

                // Simulasi proses verifikasi
                setTimeout(() => {
                    showAlert('✅ Verifikasi berhasil! (Demo - redirect ke dashboard)', 'success');

                    // Reset button setelah 2 detik
                    setTimeout(() => {
                        document.getElementById('submitBtn').disabled = false;
                        document.getElementById('submitBtn').innerHTML =
                            '<i class="bi bi-check2-circle"></i> Verifikasi & Masuk';
                    }, 2000);
                }, 1500);
            } else {
                showAlert('❌ Kode OTP salah. Gunakan 123456 untuk demo.', 'error');

                // Kosongkan input untuk coba lagi
                otpInputs.forEach(input => input.value = '');
                otpInputs[0].focus();
            }
        });

        // ========== BACK TO LOGIN ==========
        document.getElementById('backToLogin').addEventListener('click', function(e) {
            e.preventDefault();
            showAlert('Kembali ke halaman login (demo)', 'success');

            // Di sini nanti bisa redirect ke halaman login
            // window.location.href = 'login.html';
        });

        // Support untuk keyboard Enter pada back link
        document.getElementById('backToLogin').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });

        // ========== ALERT FUNCTION ==========
        function showAlert(msg, type) {
            const el = document.getElementById('jsAlert');
            const icon = type === 'error' ? 'bi bi-exclamation-triangle-fill' : 'bi bi-check-circle-fill';
            el.innerHTML = '<i class="' + icon + '"></i> ' + msg;
            el.className = 'alert show ' + type;

            // Auto hide alert setelah 3 detik
            setTimeout(() => {
                el.classList.remove('show');
            }, 3000);
        }

        // ========== INITIAL FOCUS ==========
        window.addEventListener('load', function() {
            otpInputs[0].focus();
        });

        // ========== PREVENT ZOOM ON DOUBLE TAP (opsional) ==========
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);
    </script>
</body>

</html>
