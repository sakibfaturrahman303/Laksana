<style>
    /* Warna dasar sidebar */
    .layout-menu.bg-menu-theme {
        background-color: #1e293b !important;
        /* navy gelap */
        color: #f8fafc !important;
        /* putih */
    }

    /* Header text */
    .layout-menu .menu-header-text {
        color: #94a3b8 !important;
        /* abu-abu soft */
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    /* Menu item default */
    .layout-menu .menu-item>.menu-link {
        color: #e2e8f0 !important;
        /* abu terang */
        font-weight: 500;
    }

    /* Menu item hover */
    .layout-menu .menu-item>.menu-link:hover {
        background-color: #334155 !important;
        /* navy lebih terang */
        color: #ffffff !important;
        border-radius: 0.5rem;
    }

    /* Menu item aktif */
    .layout-menu .menu-item.active>.menu-link {
        background-color: #2563eb !important;
        /* biru kontras */
        color: #ffffff !important;
        border-radius: 0.5rem;
        font-weight: 600;
    }

    /* Ikon */
    .layout-menu .menu-icon {
        color: #cbd5e1 !important;
    }

    .layout-menu .menu-item.active .menu-icon {
        color: #ffffff !important;
    }

    .app-brand-logo {
        margin-top: 20px;
        height: 150px;
        /* tinggi logo */
        width: auto;
        /* otomatis menyesuaikan */
        object-fit: contain;
    }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link d-flex align-items-center">
            <!-- Logo dari public/assets/img -->
            <img src="{{ secure_asset('assets/img/logo-laksana.png') }}" alt="Logo App" class="app-brand-logo">

            <!-- Nama aplikasi -->
            {{-- <span class="app-brand-text demo menu-text fw-bolder ms-2">
                {{ config('app.name') }}
            </span> --}}
        </a>


        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>



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

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Manajemen Alat</span>
        </li>
        <li class="menu-item {{ request()->routeIs('category.index') ? 'active' : '' }}">
            <a href="{{ route('category.index') }}" class="menu-link">
                <i class="menu-icon bx bx-grid-alt"></i>
                <div data-i18n="Data Kategori">Data Kategori</div>
            </a>
        </li>

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
        <li class="menu-item {{ request()->routeIs('borrowing.create') ? 'active' : '' }}">
            <a href="{{ route('borrowing.create') }}" class="menu-link">
                <i class="menu-icon bx bx-calendar"></i>
                <div data-i18n="Pinjam Alat">Pinjam Alat</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('borrowing.index') ? 'active' : '' }}">
            <a href="{{ route('borrowing.index') }}" class="menu-link">
                <i class="menu-icon bx bx-calendar"></i>
                <div data-i18n="Pengembalian Alat Alat">Pengembalian Alat</div>
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
