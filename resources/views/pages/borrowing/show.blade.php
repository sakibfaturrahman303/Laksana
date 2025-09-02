@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">


        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">

                <a href="{{ route('borrowing.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i>
                </a>

                <h5 class="mb-0 text-center flex-grow-1">Informasi Detail Peminjam</h5>

                <div>
                    @if ($borrowingDetails->status == 'dipinjam')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKembaliPeminjaman">
                            <i class="bx bx-check"></i>
                        </button>
                    @else
                        <a class="btn btn-info" target="_blank"
                            href="{{ route('borrowing.strukPengembalian', $borrowingDetails->id) }}">
                            <i class="bx bx-calendar-week"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <p><strong>Nama Peminjam:</strong> {{ $borrowingDetails->nama_peminjam }}</p>
                <p><strong>Status:</strong> {{ ucfirst($borrowingDetails->status) }}</p>
                <p><strong>Tanggal Pinjam:</strong>
                    {{ \Carbon\Carbon::parse($borrowingDetails->tanggal_pinjam)->format('d F Y') }}
                </p>
                <p><strong>Tanggal Kembali (Rencana):</strong>
                    {{ \Carbon\Carbon::parse($borrowingDetails->tanggal_kembali_rencana)->format('d F Y') }}
                </p>
                @if ($borrowingDetails->tanggal_kembali_aktual)
                    <p><strong>Tanggal Kembali (Aktual):</strong>
                        {{ \Carbon\Carbon::parse($borrowingDetails->tanggal_kembali_aktual)->format('d F Y') }}
                    </p>
                @endif
            </div>

            <div class="card-body">


                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>

                            <tr>
                                <th>No</th>
                                <th>Kode Alat</th>
                                <th>Nama Alat</th>
                                <th>Merk</th>
                                <th>Jumlah Dipinjam</th>
                                <th>Pengembalian</th>
                                <th>Kondisi Awal</th>
                                <th>Kondisi Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($borrowingDetails->borrowingDetails as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->tool->kode_alat }}</td>
                                    <td>{{ $detail->tool->nama_alat }}</td>
                                    <td>{{ $detail->tool->merk }}</td>
                                    <td>{{ $detail->jumlah_pinjam }}</td>
                                    <td>{{ \Carbon\Carbon::parse($borrowingDetails->tanggal_kembali_aktual)->format('d F Y') }}
                                    </td>
                                    <td>{{ $detail->kondisi_awal }}</td>
                                    <td>{{ $detail->kondisi_akhir }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada detail peminjaman</td>
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
    @endpush
@endsection
