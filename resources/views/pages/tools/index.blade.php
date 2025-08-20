@extends('layouts.app')
@section('title', 'Data Barang')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Barang</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                    <i class="bx bx-plus"></i> Tambah Barang
                </button>
            </div>

            <div class="table-responsive text-nowrap">
                <table id="dataTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>Kode Alat</th>
                            <th>Merk</th>
                            <th>Jumlah Total</th>
                            <th>Jumlah Tersedia</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tools as $tool)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tool->nama_alat }}</td>
                                <td>{{ $tool->kode_alat }}</td>
                                <td>{{ $tool->merk }}</td>
                                <td>{{ $tool->jumlah_total }}</td>
                                <td>{{ $tool->jumlah_tersedia }}</td>
                                <td>{{ $tool->category->nama_kategori ?? '-' }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditBarang{{ $tool->id }}">
                                        <i class="bx bx-edit"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalHapusBarang{{ $tool->id }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada barang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah Barang -->
        <div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="modalTambahBarangLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('tools.store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahBarangLabel">Tambah Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" name="merk" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Total</label>
                            <input type="number" name="jumlah_total" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Tersedia</label>
                            <input type="number" name="jumlah_tersedia" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit & Hapus -->
    @foreach ($tools as $tool)
        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditBarang{{ $tool->id }}" tabindex="-1"
            aria-labelledby="modalEditBarangLabel{{ $tool->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('tools.update', $tool->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control" value="{{ $tool->nama_alat }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" name="merk" class="form-control" value="{{ $tool->merk }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Total</label>
                            <input type="number" name="jumlah_total" class="form-control"
                                value="{{ $tool->jumlah_total }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Tersedia</label>
                            <input type="number" name="jumlah_tersedia" class="form-control"
                                value="{{ $tool->jumlah_tersedia }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $cat->id == $tool->category_id ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div class="modal fade" id="modalHapusBarang{{ $tool->id }}" tabindex="-1"
            aria-labelledby="modalHapusBarangLabel{{ $tool->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('tools.destroy', $tool->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus barang <strong>{{ $tool->nama_alat }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @push('scripts')
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
                    paging: false, // Hilangkan pagination bawaan
                    info: false, // Hilangkan "Showing 1 to ..."
                    ordering: false, // Hilangkan sorting kolom
                    searching: true, // Tetap ada search
                    lengthChange: true, // Dropdown jumlah data
                    language: {
                        "search": "Cari Barang:",
                        "emptyTable": "Belum ada barang tersedia",
                        "zeroRecords": "Tidak ada data yang cocok ditemukan",
                        "lengthMenu": "Tampilkan _MENU_ data"
                    },
                    dom: '<"top"lf>t'
                });
            });
        </script>
    @endpush
@endsection
