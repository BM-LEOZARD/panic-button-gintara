<div class="left-side-bar">
    <div class="brand-logo">
        <a href="" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
            <img src="{{ asset('asset/logo.png') }}" alt="" style="max-height: 50px; width: auto;">
            <span class="brand-text" style="margin-left: 10px; font-weight: bold; font-size: 1.2rem;">GINTARA.NET</span>
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">

                {{-- Dashboard --}}
                <li>
                    <a href="{{ Auth::user()->role === 'SuperAdmin' ? route('superadmin.dashboard') : route('admin.dashboard') }}"
                        class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-house-door"></span>
                        <span class="mtext">Dashboard</span>
                    </a>
                </li>

                @if (Auth::user()->role === 'SuperAdmin')
                    <li>
                        <a href="{{ route('superadmin.monitoring.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-display"></span>
                            <span class="mtext">Monitoring</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.data-wilayah.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-map"></span>
                            <span class="mtext">Data Wilayah</span>
                        </a>
                    </li>
                    <li>
                        <div class="sidebar-small-cap">Manajemen Admin</div>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.data-admin.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-person-badge"></span>
                            <span class="mtext">Data Admin</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.tugas-admin.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-list-task"></span>
                            <span class="mtext">Tugas Admin</span>
                        </a>
                    </li>
                    <li>
                        <div class="sidebar-small-cap">Manajemen Pelanggan</div>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.data-pendaftar.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-person-plus"></span>
                            <span class="mtext">Data Pendaftar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.data-pelanggan.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-people"></span>
                            <span class="mtext">Data Pelanggan</span>
                        </a>
                    </li>
                    <li>
                        <div class="sidebar-small-cap">Laporan & Audit</div>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.laporan-pelanggan.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-file-earmark-bar-graph"></span>
                            <span class="mtext">Laporan Pelanggan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.kinerja-admin.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-graph-up-arrow"></span>
                            <span class="mtext">Kinerja Admin</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role === 'Admin')
                    <li>
                        <a href="{{ route('admin.tugas.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-clipboard-check"></span>
                            <span class="mtext">Tugas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.laporan.index') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-file-earmark-text"></span>
                            <span class="mtext">Laporan</span>
                        </a>
                    </li>
                @endif
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                        @csrf
                        <a href="#" class="dropdown-toggle no-arrow"
                            onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                            <span class="micon bi bi-box-arrow-right"></span>
                            <span class="mtext">Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
