@extends('layouts.app')
@section('title', 'History Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bx bx-history me-1"></i> History Peminjaman
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table id="dataTable" class="table table-striped table-bordered align-middle">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama Peminjam</th>
                                <th>Barang</th>
                                <th>Status</th>
                                <th>Tanggal Pinjam</th>

                                <th>Tgl Kembali (Aktual)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($borrowings as $borrowing)
                                <tr data-href="{{ route('history.detail', $borrowing->id) }}" style="cursor: pointer;">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $borrowing->nama_peminjam }}</td>
                                    <td class="text-start">
                                        <div style="max-width: 200px; overflow-x: auto; white-space: nowrap;">
                                            @php
                                                $tools = $borrowing->borrowingDetails
                                                    ->map(function ($d) {
                                                        return optional($d->tool)->nama_alat;
                                                    })
                                                    ->filter()
                                                    ->toArray();
                                            @endphp
                                            {{ count($tools) ? implode(', ', $tools) : '-' }}
                                        </div>
                                    </td>

                                    <td>
                                        @php
                                            $badgeClass = match ($borrowing->status) {
                                                'dipinjam' => 'bg-warning',
                                                'dikembalikan' => 'bg-success',
                                                'terlambat' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($borrowing->status) }}
                                        </span>
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d F Y') }}</td>
                                    {{-- <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_kembali_rencana)->format('d F Y') }}
                                    </td> --}}
                                    <td>
                                        {{ $borrowing->tanggal_kembali_aktual
                                            ? \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->format('d F Y')
                                            : '-' }}
                                    </td>
                                    <td class="aksi">
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalHapusPeminjaman{{ $borrowing->id }}">
                                            <i class="bx bx-trash"></i>
                                        </button>

                                        <a class="btn btn-info btn-sm" target="_blank"
                                            href="{{ route('borrowing.strukPengembalian', $borrowing->id) }}">
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
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    responsive: true,
                    paging: false,
                    info: false,
                    ordering: false,
                    searching: true,
                    lengthChange: true,
                    language: {
                        "search": "Cari Peminjaman:",
                        "emptyTable": "Tidak ada riwayat peminjaman",
                        "zeroRecords": "Tidak ada data yang cocok ditemukan",
                        "lengthMenu": "Tampilkan _MENU_ data"
                    },
                    dom: '<"top"lf>t'
                });
            });

            // Klik row menuju detail
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll("tr[data-href]").forEach(function(row) {
                    row.addEventListener("click", function(e) {
                        if (!e.target.closest(".aksi")) {
                            window.location = this.dataset.href;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
