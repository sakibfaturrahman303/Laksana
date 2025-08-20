@extends('layouts.app')
@section('title', 'Barang dalam Kategori: ' . $category->nama_kategori)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Barang Kategori: {{ $category->nama_kategori }}</h5>
                <a href="{{ route('category.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back"></i></a>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Alat</th>
                            <th>Nama Alat</th>
                            <th>Merk</th>
                            <th>Jumlah Total</th>
                            <th>Jumlah Tersedia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($category->tool as $tools)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tools->kode_alat }}</td>
                                <td>{{ $tools->nama_alat }}</td>
                                <td>{{ $tools->merk }}</td>
                                <td>{{ $tools->jumlah_total }}</td>
                                <td>{{ $tools->jumlah_tersedia }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada barang di kategori ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
            });
        </script>
    @endpush
@endsection
