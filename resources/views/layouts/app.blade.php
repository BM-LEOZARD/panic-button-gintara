<!DOCTYPE html>
<html>

<head>
    @vite(['resources/js/app.js'])
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Dashboard - Panic Button</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('asset/dashboard/vendors/images/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('asset/logo.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('asset/logo.png') }}" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/dashboard/vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/dashboard/vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('asset/dashboard/src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('asset/dashboard/src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/dashboard/vendors/styles/style.css') }}" />
</head>

<body>

    <!-- Header -->
    @include('layouts.header')

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Navbar -->
    @include('layouts.navbar')

    <div class="mobile-menu-overlay"></div>

    <div class="main-container" style="display:flex; flex-direction:column; min-height:100vh;">
        <div style="flex:1;">
            @yield('content')
        </div>
        @include('layouts.footer')
    </div>

    <!-- JS -->
    @stack('before-scripts')
    <script src="{{ asset('asset/dashboard/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('asset/dashboard/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('asset/dashboard/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('asset/dashboard/vendors/scripts/layout-settings.js') }}"></script>
    <script src="{{ asset('asset/dashboard/src/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('asset/dashboard/src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('asset/dashboard/src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('asset/dashboard/src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('asset/dashboard/src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('asset/dashboard/vendors/scripts/dashboard3.js') }}"></script>
    @stack('dashboard-script')

    @auth
        <script>
            function showNotif(type, title, text, duration = 6000) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: type,
                    title: title,
                    text: text,
                    showConfirmButton: false,
                    timer: duration,
                    timerProgressBar: true,
                });
                addNotifikasi(type, title, text);
            }

            @if (Auth::user()->role === 'Admin')

                @foreach (Auth::user()->tugasAdmin as $tugas)
                    window.Echo.private('wilayah.{{ $tugas->wilayah_cover_id }}')
                        .listen('.panic.triggered', (e) => {
                            showNotif(
                                'warning',
                                '🚨 ALARM DARURAT!',
                                `${e.pelanggan_nama} — Blok ${e.blok}/${e.nomor} (${e.wilayah_nama})`,
                                0
                            );
                        });
                @endforeach

                window.Echo.private('admin.{{ Auth::id() }}')
                    .listen('.tugas.ditugaskan', (e) => {
                        showNotif(
                            'info',
                            '📋 Wilayah Baru Ditugaskan',
                            `Anda ditugaskan di wilayah ${e.wilayah_nama} (${e.wilayah_kode})`
                        );
                    });
            @endif

            @if (Auth::user()->role === 'SuperAdmin')

                window.Echo.private('superadmin')
                    .listen('.panic.triggered', (e) => {
                        showNotif(
                            'warning',
                            '🚨 Panic Button Aktif!',
                            `${e.pelanggan_nama} — ${e.wilayah_nama}`,
                            0
                        );
                    })
                    .listen('.alarm.diproses', (e) => {
                        showNotif(
                            'info',
                            '🚀 Admin Menuju Lokasi',
                            `${e.admin_nama} menangani ${e.pelanggan_nama} di ${e.wilayah_nama}`
                        );
                    })
                    .listen('.alarm.selesai', (e) => {
                        showNotif(
                            'success',
                            '✅ Tugas Selesai',
                            `${e.admin_nama} selesai menangani ${e.pelanggan_nama}`
                        );
                    })
                    .listen('.pendaftaran.baru', (e) => {
                        showNotif(
                            'info',
                            '📝 Pendaftaran Baru',
                            `${e.nama} mendaftar di wilayah ${e.wilayah}`
                        );
                    });
            @endif
        </script>
    @endauth
</body>

</html>
