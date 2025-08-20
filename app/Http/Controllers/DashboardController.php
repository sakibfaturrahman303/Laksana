<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama (umum, bisa dipakai admin/operator)
        $totalKategori = Category::count();
        $totalAlat = Tool::count();
        $alatTersedia = Tool::sum('jumlah_tersedia');
        $totalPeminjaman = Borrowing::count();
        $peminjamanAktif = Borrowing::where('status', 'dipinjam')->count();
        $peminjamanSelesai = Borrowing::where('status', 'selesai')->count();
        $alatDipinjam = Borrowing::where('status', 'dipinjam')->with('borrowingDetails')
            ->get()
            ->sum(function ($borrowing) {
                return $borrowing->borrowingDetails->sum('jumlah_pinjam');
            });

        // Statistik kategori dengan jumlah alat
        $kategoriStatistik = Category::withCount('tool')->get();

        // Statistik peminjaman per bulan (untuk chart)
        $borrowingsPerMonth = Borrowing::select(
            DB::raw("DATE_FORMAT(tanggal_pinjam, '%Y-%m') as bulan"),
            DB::raw("COUNT(*) as total")
        )
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->pluck('total', 'bulan');

        // Cek role user
        if (auth()->user()->role === 'admin') {
            return view('pages.admin.dashboard.index', compact(
                'totalKategori',
                'totalAlat',
                'alatTersedia',
                'alatDipinjam',
                'totalPeminjaman',
                'peminjamanAktif',
                'peminjamanSelesai',
                'kategoriStatistik',
                'borrowingsPerMonth'
            ));
        } else {
            // Jika operator
            return view('pages.operator.dashboard.index', compact(
                'totalAlat',
                'alatTersedia',
                'alatDipinjam',
                'totalPeminjaman',
                'peminjamanAktif',
                'peminjamanSelesai',
                'borrowingsPerMonth'
            ));
        }
    }
}
