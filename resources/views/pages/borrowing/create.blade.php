@extends('layouts.app')

@section('title', 'Form Peminjaman Alat')

@push('css')
    <style>
        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }

        .table-peminjaman tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 2em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-lg-12 mt-5">
            <form action="{{ route('borrowing.store') }}" method="POST" id="form-peminjaman">
                @csrf

                <!-- Info Peminjam -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informasi Peminjam</h5>
                        <a href="{{ route('borrowing.index') }}" class="btn btn-secondary"><i
                                class="bx bx-arrow-back"></i></a>
                    </div>
                    <div class="card-body mt-3">
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Nama Peminjam</label>
                            <div class="col-md-9">
                                <input type="text" name="nama_peminjam" class="form-control"
                                    value="{{ old('nama_peminjam') }}" placeholder="Masukkan nama peminjam" required>
                                @error('nama_peminjam')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Tanggal Pinjam</label>
                            <div class="col-md-9">
                                <input type="date" name="tanggal_pinjam" class="form-control"
                                    value="{{ old('tanggal_pinjam') }}" required>
                                @error('tanggal_pinjam')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Tanggal Kembali (Rencana)</label>
                            <div class="col-md-9">
                                <input type="date" name="tanggal_kembali_rencana" class="form-control"
                                    value="{{ old('tanggal_kembali_rencana') }}" required>
                                @error('tanggal_kembali_rencana')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Nama Program</label>
                            <div class="col-md-9">
                                <input type="text" name="keperluan" class="form-control" value="{{ old('keperluan') }}"
                                    placeholder="Masukkan keperluan program" required>
                                @error('keperluan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Keterangan</label>
                            <div class="col-md-9">
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Tambahkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pilih Alat -->
                <div class="card mb-3">
                    <div class="card-header border-bottom d-flex justify-content-between">
                        <h4 class="card-title">Daftar Alat</h4>
                        <button type="button" class="btn btn-primary btn-sm" onclick="$('#modalAlat').modal('show')">
                            <i class="bx bx-plus"></i> Pilih Alat
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-alat">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Merk</th>
                                        <th>Jumlah Tersedia</th>
                                        <th>Jumlah Pinjam</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada alat dipilih.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @error('tools')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        @if ($errors->has('tools.*.jumlah_pinjam'))
                            <small class="text-danger">{{ $errors->first('tools.*.jumlah_pinjam') }}</small>
                        @endif
                    </div>
                </div>

                <!-- Submit -->
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-success">Ajukan Peminjaman</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pilih Alat -->
    <div class="modal fade" id="modalAlat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Alat yang akan dipinjam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Merk</th>
                                    <th>Jumlah Tersedia</th>
                                    <th style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tools as $tool)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tool->kode_alat }}</td>
                                        <td>{{ $tool->nama_alat }}</td>
                                        <td>{{ $tool->merk }}</td>
                                        <td>{{ $tool->jumlah_tersedia }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="tambahAlat({{ $tool->id }}, '{{ addslashes($tool->kode_alat) }}', '{{ addslashes($tool->nama_alat) }}', '{{ addslashes($tool->merk) }}', {{ $tool->jumlah_tersedia }})">
                                                <i class="bx bx-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let alatDipilih = [];

            function tambahAlat(id, kode, nama, merk, tersedia) {
                if (alatDipilih.find(a => a.id == id)) {
                    $('#modalAlat').modal('hide');
                    return;
                }
                alatDipilih.push({
                    id,
                    kode,
                    nama,
                    merk,
                    tersedia,
                    jumlah: 1
                });
                renderTableAlat();
                $('#modalAlat').modal('hide');
            }

            function hapusAlat(id) {
                alatDipilih = alatDipilih.filter(a => a.id != id);
                renderTableAlat();
            }

            function updateJumlah(id, val) {
                let alat = alatDipilih.find(a => a.id == id);
                if (alat) {
                    alat.jumlah = Math.max(1, Math.min(alat.tersedia, parseInt(val) || 1));
                    renderTableAlat();
                }
            }

            function renderTableAlat() {
                let tbody = $('#table-alat tbody');
                tbody.empty();
                if (alatDipilih.length === 0) {
                    tbody.append('<tr><td colspan="7" class="text-center">Belum ada alat dipilih.</td></tr>');
                } else {
                    alatDipilih.forEach((alat, idx) => {
                        tbody.append(`
                <tr>
                    <td>${idx+1}</td>
                    <td>
                        <input type="hidden" name="tools[${idx}][tool_id]" value="${alat.id}">
                        ${alat.kode}
                    </td>
                    <td>${alat.nama}</td>
                    <td>${alat.merk}</td>
                    <td>${alat.tersedia}</td>
                    <td>
                        <input type="number" name="tools[${idx}][jumlah_pinjam]" class="form-control" 
                            min="1" max="${alat.tersedia}" value="${alat.jumlah}" 
                            onchange="updateJumlah(${alat.id}, this.value)">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusAlat(${alat.id})">Hapus</button>
                    </td>
                </tr>
            `);
                    });
                }
            }

            $(function() {
                alatDipilih = [];
                renderTableAlat();
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (session('success'))
                    showToast('success', '{{ session('success') }}', 'Sukses');
                @endif

                @if (session('error'))
                    showToast('error', '{{ session('error') }}', 'Error');
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        showToast('error', '{{ $error }}', 'Validasi');
                    @endforeach
                @endif
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    responsive: true,
                    paging: false,
                    info: false, // Hilangkan "Showing 1 to ..."
                    ordering: false, // Hilangkan sorting kolom
                    searching: true, // Tetap ada search
                    lengthChange: true, // Dropdown jumlah data
                    language: {
                        "search": "Cari Alat:",
                        "emptyTable": "Tidak ada riwayat peminjaman",
                        "zeroRecords": "Tidak ada data yang cocok ditemukan",
                        "lengthMenu": "Tampilkan _MENU_ data"
                    },
                    dom: '<"top"lf>t'
                });
            });
        </script>
    @endpush
@endsection
