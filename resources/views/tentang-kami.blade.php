<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tentang Kami - Warning System & Panic Button | Gintara Net</title>
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
            margin-bottom: 30px;
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
            transition: color 0.3s ease;
            font-size: 16px;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #0066cc;
        }

        .nav-links a.active {
            font-weight: 600;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 4px;
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

        /* Header with Logo and Panic Button */
        .header-with-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .header-logo {
            height: 70px;
            width: auto;
            object-fit: contain;
        }

        .panic-icon-small {
            width: 70px;
            height: 70px;
            background-color: #cc0000;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
        }

        .panic-icon-small .icon i {
            color: #ffd700;
        }

        .panic-icon-small .icon {
            font-size: 28px;
            line-height: 1;
        }

        .panic-icon-small .text {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-title {
            color: #1a2634;
            font-size: 48px;
            font-weight: 700;
        }

        .separator {
            height: 1px;
            background: #e2e8f0;
            margin: 20px 0 30px 0;
            width: 100%;
        }

        .main-title {
            color: #1a2634;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .content-text {
            max-width: 800px;
            text-align: center;
        }

        .content-text p {
            color: #4a5568;
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .content-text strong {
            color: #0066cc;
            font-weight: 600;
        }

        .content-text .highlight-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            border-left: 4px solid #0066cc;
            text-align: left;
        }

        .content-text .highlight-box p {
            margin-bottom: 10px;
            font-size: 16px;
            color: #4a5568;
        }

        .content-text .highlight-box i {
            color: #0066cc;
            margin-right: 10px;
            width: 20px;
        }

        .copyright {
            margin-top: 30px;
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

            .header-title {
                font-size: 42px;
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
                margin-bottom: 25px;
            }

            .container {
                padding: 25px;
            }

            .header-title {
                font-size: 36px;
            }

            .main-title {
                font-size: 28px;
            }

            .content-text p {
                font-size: 16px;
            }

            .header-logo {
                height: 60px;
            }

            .panic-icon-small {
                height: 60px;
                width: 60px;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }

            .logo-text {
                font-size: 18px;
            }

            .header-with-logo {
                gap: 20px;
            }

            .header-logo {
                height: 50px;
            }

            .panic-icon-small {
                width: 50px;
                height: 50px;
            }

            .panic-icon-small .icon {
                font-size: 22px;
            }

            .panic-icon-small .text {
                font-size: 10px;
            }

            .header-title {
                font-size: 32px;
            }

            .main-title {
                font-size: 24px;
            }

            .content-text p {
                font-size: 15px;
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

            .header-with-logo {
                gap: 15px;
            }

            .header-logo {
                height: 45px;
            }

            .panic-icon-small {
                width: 45px;
                height: 45px;
            }

            .panic-icon-small .icon {
                font-size: 20px;
            }

            .panic-icon-small .text {
                font-size: 9px;
            }

            .header-title {
                font-size: 28px;
            }

            .main-title {
                font-size: 22px;
            }

            .content-text p {
                font-size: 14px;
            }

            .highlight-box {
                padding: 16px;
            }

            .copyright {
                margin-top: 40px;
                padding-top: 20px;
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

            .header-with-logo {
                gap: 10px;
            }

            .header-logo {
                height: 40px;
            }

            .panic-icon-small {
                width: 40px;
                height: 40px;
            }

            .panic-icon-small .icon {
                font-size: 18px;
            }

            .panic-icon-small .text {
                font-size: 8px;
            }

            .header-title {
                font-size: 24px;
            }

            .main-title {
                font-size: 20px;
            }

            .content-text p {
                font-size: 13px;
            }

            .copyright {
                margin-top: 30px;
                padding-top: 16px;
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
                <a href="{{ url('/tentang-kami') }}" class="active">Tentang Kami</a>
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
                <a href="{{ url('/') }}">
                    <i class="bi bi-house-fill"></i> Beranda
                </a>
                <a href="{{ url('/tentang-kami') }}" class="active">
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

        <!-- Header with Logo and Panic Button -->
        <div class="header-with-logo">
            <img src="{{ asset('asset/logo.png') }}" alt="Logo Gintara Net" class="header-logo" />
            <span class="header-title">×</span>
            <div class="panic-icon-small">
                <div class="icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div class="text">PANIC</div>
            </div>
        </div>

        <div class="separator"></div>

        <h2 class="main-title">Tentang Gintara Net</h2>

        <div class="content-wrapper">
            <div class="content-text">
                <p>
                    <strong>Gintara Net</strong> adalah penyedia layanan internet WiFi
                    yang berlokasi di <strong>Kota Cirebon, Jawa Barat</strong>. Kami hadir
                    untuk memberikan solusi koneksi internet yang cepat, stabil, dan andal
                    bagi masyarakat dan pelaku usaha di wilayah Cirebon dan sekitarnya.
                </p>
                <p>
                    Sebagai bentuk komitmen kami terhadap kualitas layanan, kami menghadirkan
                    sistem <strong>Warning System & Panic Button</strong> — sebuah platform
                    pelaporan darurat yang memungkinkan pelanggan mendapatkan respons cepat
                    kapan pun dibutuhkan. Setiap laporan ditangani secara serius dan profesional
                    demi memastikan ketenangan dan keamanan pelanggan kami.
                </p>
                <div class="highlight-box">
                    <p><i class="bi bi-wifi"></i> <strong>Layanan:</strong> Internet WiFi untuk Hunian dan Bisnis</p>
                    <p><i class="bi bi-geo-alt-fill"></i> <strong>Area:</strong> Kota Cirebon dan Sekitarnya</p>
                    <p><i class="bi bi-clock-history"></i> <strong>Respons:</strong> Cepat, Terstruktur, dan Terpercaya
                    </p>
                    <p><i class="bi bi-shield-fill-check"></i> <strong>Komitmen:</strong> Keamanan dan Kepuasan
                        Pelanggan</p>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p>© <span id="footer-year"></span> Gintara Net. All rights reserved. - Layanan Internet WiFi Cepat & Stabil
            </p>
            <p style="margin-top: 10px">Warning System & Panic Button - Solusi Cepat untuk Pelanggan Gintara Net</p>
            <script>
                document.getElementById('footer-year').textContent = new Date().getFullYear();
            </script>
        </div>

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
