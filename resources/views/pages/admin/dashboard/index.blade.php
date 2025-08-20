@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <!-- Total Kategori -->
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar flex-shrink-0 bg-label-primary p-3 rounded me-3">
                            <i class="bx bx-layer fs-2 text-primary"></i>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Total Kategori</span>
                            <h3 class="card-title mb-0">{{ $totalKategori }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Alat -->
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar flex-shrink-0 bg-label-info p-3 rounded me-3">
                            <i class="bx bx-wrench fs-2 text-info"></i>
                        </div>
                        <div>
                            <span class="fw-semibold d-block mb-1">Total Alat</span>
                            <h3 class="card-title mb-0">{{ $totalAlat }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alat Tersedia -->
            <div class="col-lg-3 col-md-6 col-6 mb-4">
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
            <div class="col-lg-3 col-md-6 col-6 mb-4">
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
        </div>

        <!-- Statistik Peminjaman -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar flex-shrink-0 bg-label-secondary p-3 rounded me-3">
                            <i class="bx bx-book-content fs-2 text-secondary"></i>
                        </div>
                        <div>
                            <h5>Total Peminjaman</h5>
                            <h3 class="mb-0">{{ $totalPeminjaman }}</h3>
                            <small class="text-muted">Semua data</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar flex-shrink-0 bg-label-danger p-3 rounded me-3">
                            <i class="bx bx-time-five fs-2 text-danger"></i>
                        </div>
                        <div>
                            <h5>Peminjaman Aktif</h5>
                            <h3 class="mb-0">{{ $peminjamanAktif }}</h3>
                            <small class="text-warning">Sedang dipinjam</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar flex-shrink-0 bg-label-success p-3 rounded me-3">
                            <i class="bx bx-check-square fs-2 text-success"></i>
                        </div>
                        <div>
                            <h5>Peminjaman Selesai</h5>
                            <h3 class="mb-0">{{ $peminjamanSelesai }}</h3>
                            <small class="text-success">Sudah dikembalikan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Peminjaman Per Bulan -->
        <div class="card">
            <h5 class="card-header">Statistik Peminjaman per Bulan</h5>
            <div class="card-body">
                <canvas id="borrowingChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('borrowingChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($borrowingsPerMonth->keys()) !!},
                datasets: [{
                    label: 'Total Peminjaman',
                    data: {!! json_encode($borrowingsPerMonth->values()) !!},
                    borderColor: '#696CFF',
                    backgroundColor: 'rgba(105,108,255,0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>
@endsection
