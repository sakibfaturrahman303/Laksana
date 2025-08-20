@extends('layouts.app')
@section('title', 'Data Kategori')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Kategori</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahCategory">
                    <i class="bx bx-plus"></i> Tambah Kategori
                </button>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($category as $ca)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ca->nama_kategori }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditCategory{{ $ca->id }}">
                                        <i class="bx bx-edit"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <a type="button" class="btn btn-success"
                                        href="{{ route('category.show', $ca->id) }}"><i class="bx bx-show"></i>
                                    </a>
                                    <!-- Tombol Hapus -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalHapusCategory{{ $ca->id }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah Kategori -->
        <div class="modal fade" id="modalTambahCategory" tabindex="-1" aria-labelledby="modalTambahCategoryLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('category.store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCategoryLabel">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control"
                                placeholder="Masukkan nama kategori" required>
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
    <!-- Modal Edit Kategori -->
    @foreach ($category as $ca)
        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditCategory{{ $ca->id }}" tabindex="-1"
            aria-labelledby="modalEditCategoryLabel{{ $ca->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('category.update', $ca->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditCategoryLabel{{ $ca->id }}">Edit
                            Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori{{ $ca->id }}" class="form-label">Nama
                                Kategori</label>
                            <input type="text" name="nama_kategori" id="nama_kategori{{ $ca->id }}"
                                class="form-control" value="{{ $ca->nama_kategori }}" required>
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
        <div class="modal fade" id="modalHapusCategory{{ $ca->id }}" tabindex="-1"
            aria-labelledby="modalHapusCategoryLabel{{ $ca->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('category.destroy', $ca->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHapusCategoryLabel{{ $ca->id }}">Hapus
                            Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus kategori
                            <strong>{{ $ca->nama_kategori }}</strong>?
                        </p>
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
                        "search": "Cari Riwayat:",
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
