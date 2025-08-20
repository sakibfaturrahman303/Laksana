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
                                <th>Status</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tgl Kembali (Rencana)</th>
                                <th>Tgl Kembali (Aktual)</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($borrowings as $borrowing)
                                <tr data-href="{{ route('history.detail', $borrowing->id) }}" style="cursor: pointer;">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $borrowing->nama_peminjam }}</td>
                                    <td>
                                        @if ($borrowing->status === 'dipinjam')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bx bx-book-reader me-1"></i> DIPINJAM
                                            </span>
                                        @elseif ($borrowing->status === 'dikembalikan')
                                            <span class="badge bg-success">
                                                <i class="bx bx-check-circle me-1"></i> SELESAI
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ strtoupper($borrowing->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d F Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($borrowing->tanggal_kembali_rencana)->format('d F Y') }}
                                    </td>
                                    <td>
                                        {{ $borrowing->tanggal_kembali_aktual
                                            ? \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->format('d F Y')
                                            : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        <i class="bx bx-info-circle me-1"></i> Tidak ada riwayat peminjaman
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
                        "search": "Cari Peminjam:",
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
