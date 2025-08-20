<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <i class="bx bx-cube-alt bx-lg"></i>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Manajemen Dashboard</span>
        </li>
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Alat -->
        @if (auth()->user()->role == 'admin')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Manajemen Alat</span>
            </li>
            <li class="menu-item {{ request()->routeIs('category.index') ? 'active' : '' }}">
                <a href="{{ route('category.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-grid-alt"></i>
                    <div data-i18n="Data Kategori">Data Kategori</div>
                </a>
            </li>
        @endif

        <li class="menu-item {{ request()->routeIs('tools.index') ? 'active' : '' }}">
            <a href="{{ route('tools.index') }}" class="menu-link">
                <i class="menu-icon bx bx-server"></i>
                <div data-i18n="Data Alat">Data Alat</div>
            </a>
        </li>

        <!-- Peminjaman -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Manajemen Peminjaman</span>
        </li>
        <li class="menu-item {{ request()->routeIs('borrowing.index') ? 'active' : '' }}">
            <a href="{{ route('borrowing.index') }}" class="menu-link">
                <i class="menu-icon bx bx-calendar"></i>
                <div data-i18n="Pinjam Alat">Pinjam Alat</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('history.index') ? 'active' : '' }}">
            <a href="{{ route('history.index') }}" class="menu-link">
                <i class="menu-icon bx bx-file"></i>
                <div data-i18n="History Peminjaman">History Peminjaman</div>
            </a>
        </li>

        <!-- Laporan -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Laporan & Statistik</span>
        </li>
        <li class="menu-item {{ request()->routeIs('report.index') ? 'active' : '' }}">
            <a href="{{ route('report.index') }}" class="menu-link">
                <i class="menu-icon bx bx-bar-chart"></i>
                <div data-i18n="Laporan">Laporan</div>
            </a>
        </li>

        <!-- User Management (hanya admin) -->
        @if (auth()->user()->role == 'admin')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Manajemen User</span>
            </li>
            <li class="menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="menu-link">
                    <i class="menu-icon bx bx-user"></i>
                    <div data-i18n="Data Pengguna">Data Pengguna</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('users.roles') ? 'active' : '' }}">
                <a href="{{ route('users.roles') }}" class="menu-link">
                    <i class="menu-icon bx bx-shield-alt-2"></i>
                    <div data-i18n="Role & Permission">Role & Permission</div>
                </a>
            </li>
        @endif
    </ul>
</aside>
