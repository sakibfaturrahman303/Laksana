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
                <!-- Filter -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <select id="filterKategori" class="form-select">
                            <option value="">-- Semua --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nama Alat</label>
                        <select id="filterAlat" class="form-select">
                            <option value="">-- Semua --</option>
                            @foreach ($tools as $t)
                                <option value="{{ $t->nama_alat }}">{{ $t->nama_alat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tombol Reset Filter -->
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" id="resetFilter" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-refresh"></i> Reset Filter
                        </button>
                    </div>
                </div>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table id="laporanTable" class="table table-striped">
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

                <!-- Info hasil filter -->
                <div class="mt-3">
                    <small class="text-muted" id="filterInfo"></small>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#laporanTable').DataTable({
                    scrollX: false,
                    paging: true,
                    pageLength: 25,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "Semua"]
                    ],
                    ordering: true,
                    searching: true,
                    info: true,
                    order: [
                        [5, 'desc']
                    ], // Sort by tanggal pinjam descending
                    language: {
                        "search": "Cari:",
                        "emptyTable": "Belum ada data",
                        "zeroRecords": "Tidak ada data yang cocok ditemukan",
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                        "infoFiltered": "(difilter dari _MAX_ total data)",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "Selanjutnya",
                            "previous": "Sebelumnya"
                        }
                    },
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
                });

                // Hapus filter sebelumnya jika ada
                if ($.fn.dataTable.ext.search.length > 0) {
                    $.fn.dataTable.ext.search.length = 0;
                }

                // Semua alat disimpan dalam JS (dari Blade)
                var allTools = @json($tools);

                // Custom filter function DataTable
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (settings.nTable.id !== 'laporanTable') {
                        return true;
                    }

                    var startDate = $('#start_date').val();
                    var endDate = $('#end_date').val();
                    var kategori = $('#filterKategori').val();
                    var alat = $('#filterAlat').val();

                    var tglPinjamStr = data[5]; // Kolom tanggal pinjam
                    var kategoriRow = data[2] ? data[2].toString().trim() : "";
                    var alatRow = data[3] ? data[3].toString().trim() : "";

                    // Filter tanggal
                    if (startDate || endDate) {
                        var tglPinjam = tglPinjamStr ? new Date(tglPinjamStr) : null;
                        if (!tglPinjam) return false;

                        if (startDate) {
                            var start = new Date(startDate);
                            if (tglPinjam < start) return false;
                        }
                        if (endDate) {
                            var end = new Date(endDate);
                            if (tglPinjam > end) return false;
                        }
                    }

                    // Filter kategori
                    if (kategori && kategori !== "") {
                        if (kategoriRow.toLowerCase() !== kategori.toLowerCase()) {
                            return false;
                        }
                    }

                    // Filter alat
                    if (alat && alat !== "") {
                        if (alatRow.toLowerCase() !== alat.toLowerCase()) {
                            return false;
                        }
                    }

                    return true;
                });

                // Update info filter
                function updateFilterInfo() {
                    var info = [];

                    if ($('#start_date').val()) {
                        info.push('Dari: ' + $('#start_date').val());
                    }
                    if ($('#end_date').val()) {
                        info.push('Sampai: ' + $('#end_date').val());
                    }
                    if ($('#filterKategori').val()) {
                        info.push('Kategori: ' + $('#filterKategori option:selected').text());
                    }
                    if ($('#filterAlat').val()) {
                        info.push('Alat: ' + $('#filterAlat option:selected').text());
                    }

                    if (info.length > 0) {
                        $('#filterInfo').html('Filter aktif: ' + info.join(' | '));
                    } else {
                        $('#filterInfo').html('');
                    }
                }

                // Event listener untuk semua filter
                $('#start_date, #end_date, #filterKategori, #filterAlat').on('change keyup', function() {
                    table.draw();
                    updateFilterInfo();
                });

                // Reset filter
                $('#resetFilter').on('click', function() {
                    $('#start_date').val('');
                    $('#end_date').val('');
                    $('#filterKategori').val('');
                    $('#filterAlat').val('');
                    table.draw();
                    updateFilterInfo();

                    // Reset dropdown alat jadi semua lagi
                    var alatDropdown = $('#filterAlat');
                    alatDropdown.empty();
                    alatDropdown.append('<option value="">-- Semua --</option>');
                    allTools.forEach(function(tool) {
                        alatDropdown.append('<option value="' + tool.nama_alat + '">' + tool.nama_alat +
                            '</option>');
                    });
                });

                // Dependent dropdown: filter alat berdasarkan kategori
                $('#filterKategori').on('change', function() {
                    var selectedKategori = $(this).val();
                    var alatDropdown = $('#filterAlat');

                    alatDropdown.empty();
                    alatDropdown.append('<option value="">-- Semua --</option>');

                    allTools.forEach(function(tool) {
                        if (selectedKategori === "" || (tool.category && tool.category.nama_kategori ===
                                selectedKategori)) {
                            alatDropdown.append('<option value="' + tool.nama_alat + '">' + tool
                                .nama_alat + '</option>');
                        }
                    });

                    $('#filterAlat').val('');
                    table.draw();
                    updateFilterInfo();
                });

                // Initialize filter info
                updateFilterInfo();

                // Update nomor urut setelah filter
                table.on('draw', function() {
                    table.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                });
            });
        </script>
    @endpush

@endsection
