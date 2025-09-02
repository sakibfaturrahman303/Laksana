@extends('layouts.app')
@section('title', 'Laporan Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Laporan Peminjaman & Pengembalian</h5>
                <div>
                    <a href="{{ route('report.export', request()->all()) }}" class="btn btn-success" target="_blank">
                        <i class="bx bx-printer"></i> Export
                    </a>
                </div>
            </div>

            <!-- Filter -->
            <div class="card-body">
                <form method="GET" action="{{ route('report.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Semua --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-filter"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Laporan -->
            <div class="table-responsive text-nowrap">
                <table id="laporanTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Nama Alat</th>
                            <th>Kode Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $data)
                            @php
                                $detail = optional($data->borrowingDetails->first());
                                $tool = optional($detail->tool);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->nama_peminjam ?? '-' }}</td>
                                <td>{{ $tool->nama_alat ?? '-' }}</td>
                                <td>{{ $tool->kode_alat ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d F Y') }}</td>
                                <td>{{ $data->tanggal_kembali_aktual ? \Carbon\Carbon::parse($data->tanggal_kembali_aktual)->format('d F Y') : '-' }}
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match ($data->status) {
                                            'dipinjam' => 'bg-warning',
                                            'dikembalikan' => 'bg-success',
                                            'terlambat' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($data->status) }}
                                    </span>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#laporanTable').DataTable({
                    responsive: true,
                    paging: true,
                    info: false,
                    ordering: false,
                    searching: true,
                    lengthChange: true,
                    language: {
                        "search": "Cari Data:",
                        "emptyTable": "Belum ada data laporan",
                        "zeroRecords": "Tidak ada data yang cocok ditemukan",
                        "lengthMenu": "Tampilkan _MENU_ data"
                    },
                    dom: '<"top"lf>t<"bottom"p>'
                });
            });
        </script>
    @endpush
@endsection
