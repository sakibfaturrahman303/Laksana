@extends('layouts.app')
@section('title', 'Daftar Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Peminjaman</h5>
                <a href="{{ route('borrowing.create') }}" type="button" class="btn btn-primary">
                    <i class="bx bx-plus"></i>Peminjaman
                </a>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Status</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali (Rencana)</th>
                            <th>Tanggal Kembali (Aktual)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borrowings as $borrowing)
                            <tr data-href="{{ route('borrowing.show', $borrowing->id) }}" style="cursor: pointer;">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $borrowing->nama_peminjam }}</td>
                                <td>
                                    <span
                                        class="badge {{ $borrowing->status == 'dipinjam' ? 'bg-warning' : 'bg-success' }}">
                                        {{ ucfirst($borrowing->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_kembali_rencana)->format('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->format('d F Y') }}</td>
                                <td class="aksi">
                                    <a class="btn btn-warning btn-sm" href="{{ route('borrowing.edit', $borrowing->id) }}">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <a class="btn btn-info btn-sm" target="_blank"
                                        href="{{ route('borrowing.strukPeminjaman', $borrowing->id) }}">
                                        <i class="bx bx-calendar-week"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalHapusPeminjaman{{ $borrowing->id }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($borrowings as $borrowing)
        <div class="modal fade" id="modalHapusPeminjaman{{ $borrowing->id }}" tabindex="-1"
            aria-labelledby="modalHapusPeminjamanLabel{{ $borrowing->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('borrowing.destroy', $borrowing->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHapusPeminjamanLabel{{ $borrowing->id }}">
                            Hapus Peminjaman
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Apakah Anda yakin ingin menghapus peminjaman
                            <strong>{{ $borrowing->nama_peminjam }}</strong>
                            dengan barang berikut?
                        </p>
                        <ul>
                            @foreach ($borrowing->borrowingDetails as $detail)
                                <li>{{ $detail->tool->nama_alat }} | {{ $detail->jumlah_pinjam }}</li>
                            @endforeach
                        </ul>
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
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll("tr[data-href]").forEach(function(row) {
                    row.addEventListener("click", function(e) {
                        // Cegah klik di kolom aksi (td.aksi atau tombol/link di dalamnya)
                        if (!e.target.closest(".aksi")) {
                            window.location = this.dataset.href;
                        }
                    });
                });
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
                    paging: false, // Hilangkan pagination bawaan
                    info: false, // Hilangkan "Showing 1 to ..."
                    ordering: false, // Hilangkan sorting kolom
                    searching: true, // Tetap ada search
                    lengthChange: true, // Dropdown jumlah data
                    language: {
                        "search": "Cari Peminjam:",
                        "emptyTable": "Belum ada peminjaman",
                        "zeroRecords": "Tidak ada data yang cocok ditemukan",
                        "lengthMenu": "Tampilkan _MENU_ data"
                    },
                    dom: '<"top"lf>t' // Hanya tampilkan length dropdown (l) + filter/search (f) + tabel (t)
                });
            });
        </script>
    @endpush
@endsection
