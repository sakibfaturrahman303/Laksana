@extends('layouts.app')
@section('title', 'Daftar Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Peminjaman</h5>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Program</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali (Rencana)</th>
                            <th>Operator</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borrowings as $borrowing)
                            <tr data-href="{{ route('borrowing.show', $borrowing->id) }}" style="cursor: pointer;">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $borrowing->nama_peminjam }}</td>
                                <td>{{ $borrowing->keperluan }}</td>

                                <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_kembali_rencana)->format('d F Y') }}</td>
                                <td>{{ $borrowing->user->name }}</td>
                                <td> <span
                                        class="badge {{ $borrowing->status == 'dipinjam' ? 'bg-warning' : 'bg-success' }}">
                                        {{ ucfirst($borrowing->status) }}
                                    </span></td>
                                <td class="aksi">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalKembaliPeminjaman{{ $borrowing->id }}">
                                        <i class="bx bx-check"></i>
                                    </button>
                                    <a class="btn btn-info btn-sm" target="_blank"
                                        href="{{ route('borrowing.strukPeminjaman', $borrowing->id) }}">
                                        <i class="bx bx-calendar-week"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($borrowings as $borrowing)
        <div class="modal fade" id="modalKembaliPeminjaman{{ $borrowing->id }}" tabindex="-1"
            aria-labelledby="modalKembaliPeminjamanLabel{{ $borrowing->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form action="{{ route('borrowing.return', $borrowing->id) }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKembaliPeminjamanLabel{{ $borrowing->id }}">
                            Pengembalian Barang - {{ $borrowing->nama_peminjam }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Alat</th>
                                    <th>Jumlah Pinjam</th>
                                    <th>Kondisi Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($borrowing->borrowingDetails as $detail)
                                    <tr>
                                        <td>{{ $detail->tool->nama_alat }}</td>
                                        <td>{{ $detail->jumlah_pinjam }}</td>
                                        <td>
                                            <select name="details[{{ $detail->id }}][kondisi_akhir]" class="form-select"
                                                required>
                                                <option value="" disabled selected>-- Pilih Kondisi --</option>
                                                <option value="Baik">Baik</option>
                                                <option value="Rusak Ringan">Rusak Ringan</option>
                                                <option value="Rusak Berat">Rusak Berat</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kembalikan</button>
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
