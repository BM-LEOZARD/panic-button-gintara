<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Warning System & Panic Button | Gintara Net</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f0f7ff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.1);
            border: 1px solid #e2e8f0;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 60px;
            flex-wrap: nowrap;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #e6f0ff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 102, 204, 0.1);
            overflow: hidden;
            padding: 6px;
            flex-shrink: 0;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-text {
            color: #0066cc;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .nav-links a:hover {
            color: #0066cc;
        }

        .btn-daftar,
        .btn-daftar:visited,
        .btn-daftar:hover {
            color: #ffffff !important;
            text-decoration: none;
        }

        .btn-masuk,
        .btn-masuk:visited {
            color: #0066cc !important;
            text-decoration: none;
        }

        .btn-masuk:hover {
            color: #004999 !important;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-masuk {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            border: 2px solid #0066cc;
            background: #ffffff;
        }

        .btn-masuk:hover {
            background: #e6f0ff;
            border-color: #004999;
        }

        .btn-daftar {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            background: #0066cc;
            border: 2px solid #0066cc;
            box-shadow: 0 4px 10px rgba(0, 102, 204, 0.2);
        }

        .btn-daftar:hover {
            background: #004999;
            border-color: #004999;
        }

        /* Hero Section */
        .hero {
            display: flex;
            align-items: center;
            gap: 50px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .hero-content {
            flex: 1;
            min-width: 300px;
        }

        .hero-content h1 {
            color: #1a2634;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-content p {
            color: #4a5568;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 600px;
        }

        .btn-get-started {
            display: inline-block;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 18px;
            background: #0066cc;
            border: none;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 102, 204, 0.2);
            text-decoration: none;
        }

        .btn-get-started:hover {
            background: #004999;
        }

        .hero-image {
            flex: 1;
            min-width: 300px;
            display: flex;
            justify-content: center;
        }

        .panic-button {
            width: 280px;
            height: 280px;
            background-color: #cc0000;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(204, 0, 0, 0.3);
            color: white;
            text-align: center;
            border: 4px solid rgba(255, 255, 255, 0.8);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .panic-button:hover {
            background-color: #e60000;
        }

        .panic-button .icon {
            font-size: 80px;
            margin-bottom: 10px;
        }

        .panic-button .icon i {
            color: #ffd700;
        }

        .panic-button .text {
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .panic-button .subtext {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }

        /* Features */
        .features {
            display: flex;
            gap: 30px;
            margin-top: 60px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .feature-card {
            background: #f8fafc;
            padding: 30px 20px;
            border-radius: 15px;
            text-align: center;
            flex: 1;
            min-width: 250px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .feature-card:hover {
            border-color: #0066cc;
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .feature-icon i {
            color: #0066cc;
        }

        .feature-card h3 {
            color: #1a2634;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #4a5568;
            line-height: 1.5;
        }

        footer {
            margin-top: 80px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }

        /* ========== HAMBURGER ========== */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 5px;
            z-index: 1001;
            margin-left: auto;
            background: none;
            border: none;
        }

        .hamburger span {
            display: block;
            width: 25px;
            height: 3px;
            background: #1a2634;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .hamburger.active span:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        body.sidebar-open .hamburger {
            display: none;
        }

        /* ========== SIDEBAR ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            background: #ffffff;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            transition: left 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo .logo-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #e6f0ff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 102, 204, 0.1);
            overflow: hidden;
            padding: 6px;
            flex-shrink: 0;
        }

        .sidebar-logo .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar-logo span {
            font-size: 16px;
            font-weight: 700;
            color: #0066cc;
            letter-spacing: 1px;
        }

        .sidebar-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #4a5568;
            line-height: 1;
            padding: 0;
        }

        .sidebar-close:hover {
            color: #cc0000;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            padding: 20px;
            gap: 5px;
        }

        .sidebar-nav a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            padding: 12px 15px;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: #f0f7ff;
            color: #0066cc;
        }

        .sidebar-nav a.active {
            font-weight: 600;
        }

        .sidebar-auth {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            border-top: 1px solid #e2e8f0;
        }

        .sidebar-auth .btn-masuk,
        .sidebar-auth .btn-daftar {
            width: 100%;
            text-align: center;
            padding: 12px 20px;
            font-size: 15px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .container {
                padding: 30px;
            }

            .hero-content h1 {
                font-size: 42px;
            }

            .panic-button {
                width: 260px;
                height: 260px;
            }

            .panic-button .icon {
                font-size: 70px;
            }
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            nav .nav-links {
                display: none;
            }

            nav {
                margin-bottom: 40px;
            }

            .container {
                padding: 25px;
            }

            .hero {
                flex-direction: column-reverse;
                text-align: center;
                gap: 30px;
            }

            .hero-content h1 {
                font-size: 36px;
                text-align: center;
            }

            .hero-content p {
                font-size: 16px;
                text-align: center;
                margin-left: auto;
                margin-right: auto;
            }

            .btn-get-started {
                display: inline-block;
                margin: 0 auto;
            }

            .panic-button {
                width: 240px;
                height: 240px;
            }

            .panic-button .icon {
                font-size: 65px;
            }

            .panic-button .text {
                font-size: 24px;
            }

            .features {
                gap: 20px;
            }

            .feature-card {
                min-width: calc(50% - 20px);
                flex: 1 1 calc(50% - 20px);
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }

            .logo-text {
                font-size: 18px;
            }

            .hero-content h1 {
                font-size: 30px;
            }

            .hero-content p {
                font-size: 15px;
            }

            .btn-get-started {
                display: block;
                width: 100%;
                text-align: center;
                padding: 12px 20px;
                font-size: 16px;
            }

            .panic-button {
                width: 200px;
                height: 200px;
            }

            .panic-button .icon {
                font-size: 55px;
            }

            .panic-button .text {
                font-size: 22px;
            }

            .panic-button .subtext {
                font-size: 12px;
            }

            .features {
                flex-direction: column;
                gap: 15px;
            }

            .feature-card {
                min-width: 100%;
                width: 100%;
                padding: 25px 20px;
            }

            .feature-icon {
                font-size: 42px;
            }

            .feature-card h3 {
                font-size: 20px;
            }

            .feature-card p {
                font-size: 14px;
            }
        }

        @media (max-width: 375px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 16px;
                border-radius: 16px;
            }

            .logo-text {
                font-size: 16px;
            }

            .hero-content h1 {
                font-size: 26px;
            }

            .hero-content p {
                font-size: 14px;
            }

            .btn-get-started {
                padding: 12px 16px;
                font-size: 15px;
            }

            .panic-button {
                width: 180px;
                height: 180px;
                border-width: 3px;
            }

            .panic-button .icon {
                font-size: 48px;
            }

            .panic-button .text {
                font-size: 20px;
            }

            .panic-button .subtext {
                font-size: 11px;
            }

            .feature-card {
                padding: 20px 16px;
            }

            .feature-icon {
                font-size: 38px;
                margin-bottom: 15px;
            }

            .feature-card h3 {
                font-size: 18px;
            }

            .feature-card p {
                font-size: 13px;
            }

            footer {
                margin-top: 50px;
                padding-top: 20px;
            }

            footer p {
                font-size: 12px;
            }
        }

        @media (max-width: 320px) {
            body {
                padding: 8px;
            }

            .container {
                padding: 12px;
                border-radius: 12px;
            }

            .logo-text {
                font-size: 14px;
            }

            .hero-content h1 {
                font-size: 22px;
                margin-bottom: 10px;
            }

            .hero-content p {
                font-size: 12px;
                line-height: 1.4;
                margin-bottom: 20px;
            }

            .btn-get-started {
                padding: 10px 12px;
                font-size: 13px;
            }

            .panic-button {
                width: 150px;
                height: 150px;
                border-width: 3px;
            }

            .panic-button .icon {
                font-size: 40px;
                margin-bottom: 5px;
            }

            .panic-button .text {
                font-size: 18px;
                letter-spacing: 1px;
            }

            .panic-button .subtext {
                font-size: 10px;
                margin-top: 2px;
            }

            .feature-card {
                padding: 15px 12px;
            }

            .feature-icon {
                font-size: 32px;
                margin-bottom: 10px;
            }

            .feature-card h3 {
                font-size: 16px;
                margin-bottom: 8px;
            }

            .feature-card p {
                font-size: 11px;
                line-height: 1.4;
            }

            footer {
                margin-top: 40px;
                padding-top: 16px;
            }

            footer p {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Navigation Desktop -->
        <nav>
            <div class="logo">
                <div class="logo-wrap">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo Gintara Net" />
                </div>
                <span class="logo-text">GINTARA.NET</span>
            </div>
            <div class="nav-links">
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                <div class="auth-buttons">
                    @auth
                        @php $role = Auth::user()->role; @endphp
                        @if ($role === 'Admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn-daftar">Dashboard</a>
                        @elseif($role === 'SuperAdmin')
                            <a href="{{ route('superadmin.dashboard') }}" class="btn-daftar">Dashboard</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn-daftar">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-masuk">Masuk</a>
                        <a href="{{ route('pendaftaran.index') }}" class="btn-daftar">Daftar</a>
                    @endauth
                </div>
            </div>

            <!-- Hamburger (mobile only) -->
            <button class="hamburger" id="hamburger" aria-label="Buka menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>

        <!-- Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar Mobile -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-wrap">
                        <img src="{{ asset('asset/logo.png') }}" alt="Logo Gintara Net" />
                    </div>
                    <span>GINTARA.NET</span>
                </div>
                <button class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">&times;</button>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ url('/') }}" class="active">
                    <i class="bi bi-house-fill"></i> Beranda
                </a>
                <a href="{{ url('/tentang-kami') }}">
                    <i class="bi bi-info-circle-fill"></i> Tentang Kami
                </a>
            </nav>

            <div class="sidebar-auth">
                @auth
                    @php $role = Auth::user()->role; @endphp
                    @if ($role === 'Admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-daftar">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @elseif($role === 'SuperAdmin')
                        <a href="{{ route('superadmin.dashboard') }}" class="btn-daftar">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-daftar">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-masuk">Masuk</a>
                    <a href="{{ route('pendaftaran.index') }}" class="btn-daftar">Daftar</a>
                @endauth
            </div>
        </div>

        <!-- Hero Section -->
        <div class="hero">
            <div class="hero-content">
                <h1>Warning System & Panic Button</h1>
                <p>
                    Butuh bantuan cepat? Tekan tombol panic untuk melaporkan masalah atau
                    situasi yang membutuhkan perhatian segera dari tim Gintara Net.
                    Kami siap membantu Anda dengan respon yang cepat.
                </p>
                <a href="{{ route('pendaftaran.index') }}" class="btn-get-started">Get Started →</a>
            </div>
            <div class="hero-image">
                <div class="panic-button">
                    <div class="icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="text">PANIC</div>
                </div>
            </div>
        </div>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-person-raised-hand"></i></div>
                <h3>Butuh Pertolongan?</h3>
                <p>Panic button dirancang untuk siapa saja yang membutuhkan bantuan segera. Satu tekan, tim kami
                    langsung bergerak.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-bell-fill"></i></div>
                <h3>Kirim Sinyal Darurat</h3>
                <p>Laporkan situasi darurat secara instan. Lokasi dan informasi Anda langsung diterima oleh tim respons
                    kami.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-shield-fill-exclamation"></i></div>
                <h3>Respon Cepat & Terpercaya</h3>
                <p>Setiap sinyal panic button ditangani dengan serius. Kami memastikan Anda mendapatkan bantuan yang
                    dibutuhkan tepat waktu.</p>
            </div>
        </div>

        <footer>
            <p>© <span id="footer-year"></span> Gintara Net. All rights reserved. - Layanan Internet WiFi Cepat & Stabil
            </p>
            <p style="margin-top: 10px">Warning System & Panic Button - Solusi Cepat untuk Pelanggan Gintara Net</p>
            <script>
                document.getElementById('footer-year').textContent = new Date().getFullYear();
            </script>
        </footer>

    </div>

    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarClose = document.getElementById('sidebarClose');

        function openSidebar() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            hamburger.classList.add('active');
            document.body.classList.add('sidebar-open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            hamburger.classList.remove('active');
            document.body.classList.remove('sidebar-open');
            document.body.style.overflow = '';
        }

        hamburger.addEventListener('click', openSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
    </script>
</body>

</html>
