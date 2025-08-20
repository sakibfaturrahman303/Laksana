@extends('layouts.app')
@section('title', 'Dashboard Operator')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <!-- Alat Tersedia -->
            <div class="col-lg-4 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar flex-shrink-0 bg-label-success p-3 rounded me-3">
                            <i class="bx bx-check-circle fs-2 text-success"></i>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Alat Tersedia</span>
                            <h3 class="card-title mb-0">{{ $alatTersedia }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alat Dipinjam -->
            <div class="col-lg-4 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar flex-shrink-0 bg-label-warning p-3 rounded me-3">
                            <i class="bx bx-package fs-2 text-warning"></i>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Alat Dipinjam</span>
                            <h3 class="card-title mb-0">{{ $alatDipinjam }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peminjaman Aktif -->
            <div class="col-lg-4 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar flex-shrink-0 bg-label-danger p-3 rounded me-3">
                            <i class="bx bx-time-five fs-2 text-danger"></i>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Peminjaman Aktif</span>
                            <h3 class="card-title mb-0">{{ $peminjamanAktif }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Akses Cepat -->
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Akses Cepat</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('tools.index') }}" class="btn btn-primary me-2">
                            <i class="bx bx-wrench me-1"></i> Kelola Alat
                        </a>
                        <a href="{{ route('borrowing.index') }}" class="btn btn-warning me-2">
                            <i class="bx bx-book me-1"></i> Kelola Peminjaman
                        </a>
                        <a href="{{ route('report.index') }}" class="btn btn-success">
                            <i class="bx bx-file me-1"></i> Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Singkat -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="bx bx-info-circle text-info"></i>
                            Selamat datang di dashboard operator. Anda dapat mengelola peminjaman alat, mengembalikan alat,
                            dan melihat laporan singkat.
                        </p>
                        <p class="mb-0">
                            <i class="bx bx-shield-quarter text-primary"></i>
                            Pastikan setiap peminjaman dan pengembalian alat dicatat dengan benar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
