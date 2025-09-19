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

            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('report.index') }}" id="filterForm">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" id="filterKategori" class="form-select">
                                <option value="">-- Semua --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->nama_kategori }}"
                                        {{ request('kategori') == $cat->nama_kategori ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nama Alat</label>
                            <select name="alat" id="filterAlat" class="form-select">
                                <option value="">-- Semua --</option>
                                @foreach ($tools as $t)
                                    <option value="{{ $t->nama_alat }}"
                                        data-kategori="{{ $t->category->nama_kategori ?? '' }}"
                                        {{ request('alat') == $t->nama_alat ? 'selected' : '' }}>
                                        {{ $t->nama_alat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 text-end"> {{-- Tambahkan text-end di sini --}}
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bx bx-search"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('report.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Kategori</th>
                                <th>Nama Alat</th>
                                <th>Kode Alat</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $index => $data)
                                @foreach ($data->borrowingDetails as $detail)
                                    @php $tool = $detail->tool; @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->nama_peminjam ?? '-' }}</td>
                                        <td>{{ $tool->category->nama_kategori ?? '-' }}</td>
                                        <td>{{ $tool->nama_alat ?? '-' }}</td>
                                        <td>{{ $tool->kode_alat ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('Y-m-d') }}</td>
                                        <td>{{ $data->tanggal_kembali_aktual ? \Carbon\Carbon::parse($data->tanggal_kembali_aktual)->format('Y-m-d') : '' }}
                                        </td>
                                        <td>{{ ucfirst($data->status) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var kategoriSelect = document.getElementById('filterKategori');
                var alatSelect = document.getElementById('filterAlat');

                function filterAlatDropdown() {
                    var selectedKategori = kategoriSelect.value;
                    var options = alatSelect.querySelectorAll('option');

                    options.forEach(function(option, index) {
                        if (index === 0) return; // skip "-- Semua --"
                        var kategoriOption = option.getAttribute('data-kategori');
                        if (selectedKategori === "" || kategoriOption === selectedKategori) {
                            option.style.display = "";
                        } else {
                            option.style.display = "none";
                            option.selected = false; // reset jika tersembunyi
                        }
                    });
                }

                // Jalankan saat kategori berubah
                kategoriSelect.addEventListener('change', filterAlatDropdown);

                // Jalankan sekali waktu load halaman (biar sinkron dengan request)
                filterAlatDropdown();
            });
        </script>
    @endpush
@endsection
