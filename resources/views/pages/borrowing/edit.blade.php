@extends('layouts.app')

@section('title', 'Edit Peminjaman Alat')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-lg-12 mt-5">
            <form action="{{ route('borrowing.update', $borrowing->id) }}" method="POST" id="form-peminjaman">
                @csrf

                <!-- Info Peminjam -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Informasi Peminjaman</h5>
                        <a href="{{ route('borrowing.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i>
                        </a>
                    </div>
                    <div class="card-body mt-3">
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Nama Peminjam</label>
                            <div class="col-md-9">
                                <input type="text" name="nama_peminjam" class="form-control"
                                    value="{{ old('nama_peminjam', $borrowing->nama_peminjam) }}" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Tanggal Pinjam</label>
                            <div class="col-md-9">
                                <input type="date" name="tanggal_pinjam" class="form-control"
                                    value="{{ old('tanggal_pinjam', $borrowing->tanggal_pinjam) }}" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Tanggal Kembali (Rencana)</label>
                            <div class="col-md-9">
                                <input type="date" name="tanggal_kembali_rencana" class="form-control"
                                    value="{{ old('tanggal_kembali_rencana', $borrowing->tanggal_kembali_rencana) }}"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Nama Program</label>
                            <div class="col-md-9">
                                <input type="text" name="keperluan" class="form-control"
                                    value="{{ old('keperluan', $borrowing->keperluan) }}" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label">Keterangan</label>
                            <div class="col-md-9">
                                <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $borrowing->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pilih Alat -->
                <div class="card mb-3">
                    <div class="card-header border-bottom d-flex justify-content-between">
                        <h4 class="card-title">Daftar Alat</h4>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalAlat">
                            <i class="bx bx-plus"></i> Tambah Alat
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
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-success">Update Peminjaman</button>
                    </div>
                </div>

                <!-- Modal Pilih Alat (MENYATU DI FORM) -->
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
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let alatDipilih = @json($alatDipilih);

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
                        <td><input type="hidden" name="tools[${idx}][tool_id]" value="${alat.id}">${alat.kode}</td>
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
                renderTableAlat();
            });
        </script>
    @endpush
@endsection
