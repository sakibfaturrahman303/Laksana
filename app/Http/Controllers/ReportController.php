<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BorrowingExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function cetakPeminjaman($id)
    {
        $borrowing = Borrowing::with(['borrowingDetails.tool', 'operatorPinjam', 'operatorKembali'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pages.report.strukPeminjaman', compact('borrowing'))
                  ->setPaper('A4', 'portrait');

        return $pdf->stream("Struk-Peminjaman-{$borrowing->id}.pdf");
    }

    public function cetakPengembalian($id)
    {
        $borrowing = Borrowing::with(['borrowingDetails.tool', 'operatorPinjam', 'operatorKembali'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pages.report.strukPengembalian', compact('borrowing'))
                  ->setPaper('A4', 'landscape'); 

        return $pdf->stream("Struk-Pengembalian-{$borrowing->id}.pdf");
    }

    public function history()
    {
        $history = Borrowing::with(['borrowingDetails.tool', 'operatorPinjam', 'operatorKembali'])
            ->get();

        $pdf = Pdf::loadView('pages.report.print', compact('history'))
                  ->setPaper('A4', 'landscape');

        return $pdf->stream("Riwayat Peminjaman.pdf");
    }

  public function index(Request $request)
{
    $query = Borrowing::with(['borrowingDetails.tool.category', 'operatorPinjam', 'operatorKembali'])
                     ->whereIn('status', ['selesai', 'terlambat']);

    // Filter tanggal
    if ($request->start_date) {
        $query->where('tanggal_pinjam', '>=', $request->start_date);
    }
    
    if ($request->end_date) {
        $query->where('tanggal_pinjam', '<=', $request->end_date);
    }

    // Filter kategori berdasarkan nama kategori
    if ($request->kategori) {
        $query->whereHas('borrowingDetails.tool.category', function ($q) use ($request) {
            $q->where('nama_kategori', $request->kategori);
        });
    }

    // Filter alat berdasarkan nama alat
    if ($request->alat) {
        $query->whereHas('borrowingDetails.tool', function ($q) use ($request) {
            $q->where('nama_alat', $request->alat);
        });
    }

    $laporan = $query->latest('tanggal_pinjam')->get();

    // untuk filter dropdown
   $categories = Category::orderBy('nama_kategori', 'asc')->get();
    $tools = Tool::with('category')
                ->orderBy('nama_alat', 'asc')
                ->get();

    return view('pages.report.index', compact('laporan', 'categories', 'tools'));

}

    public function exportPdf(Request $request)
{
    $query = Borrowing::with(['borrowingDetails.tool.category', 'operatorPinjam', 'operatorKembali']);

    // Filter tanggal
    if ($request->start_date && $request->end_date) {
        $query->whereBetween('tanggal_pinjam', [$request->start_date, $request->end_date]);
    }

    // Filter status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // Filter kategori (by nama_kategori)
    if ($request->filterKategori) {
        $kategori = $request->filterKategori;
        $query->whereHas('borrowingDetails.tool.category', function ($q) use ($kategori) {
            $q->where('nama_kategori', $kategori);
        });
    }

    // Filter nama alat
    if ($request->filterAlat) {
        $alat = $request->filterAlat;
        $query->whereHas('borrowingDetails.tool', function ($q) use ($alat) {
            $q->where('nama_alat', $alat);
        });
    }

    $history = $query->latest()->get();

    $pdf = Pdf::loadView('pages.report.print', compact('history'))
              ->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-peminjaman.pdf');
}

}
