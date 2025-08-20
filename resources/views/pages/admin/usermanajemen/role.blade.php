@extends('layouts.app')
@section('title', 'Role & Hak Akses')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Role & Hak Akses</h5>
            </div>
            <div class="card-body">
                <p class="mb-4">
                    Sistem ini menggunakan <strong>Role</strong> untuk membedakan hak akses pengguna.
                </p>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Role</th>
                                <th>Deskripsi</th>
                                <th>Hak Akses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-primary">Admin</span></td>
                                <td>
                                    Admin memiliki kendali penuh terhadap sistem.
                                </td>
                                <td>
                                    <ul class="mb-0">
                                        <li>Kelola data user</li>
                                        <li>Kelola data barang</li>
                                        <li>Melihat semua laporan</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Operator</span></td>
                                <td>
                                    Operator bertugas mengelola data barang sehari-hari.
                                </td>
                                <td>
                                    <ul class="mb-0">
                                        <li>Input dan update data barang</li>
                                        <li>Melihat stok barang</li>
                                        <li>Tidak bisa mengubah data user</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-4" role="alert">
                    <i class="bx bx-info-circle"></i>
                    Jika Anda membutuhkan perubahan role, silakan hubungi <strong>Admin</strong>.
                </div>
            </div>
        </div>
    </div>
@endsection
