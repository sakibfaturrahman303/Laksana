<?php

namespace App\Http\Controllers;

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
        $borrowing = Borrowing::with(['borrowingDetails.tool', 'user'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.report.strukPeminjaman', compact('borrowing'))
                  ->setPaper('A4', 'portrait');

        return $pdf->stream("Struk-Peminjaman-{$borrowing->id}.pdf");
    }

    public function cetakPengembalian($id)
    {
        $borrowing = Borrowing::with(['borrowingDetails.tool', 'user'])->findOrFail($id);

        $pdf = Pdf::loadView('pages.report.strukPengembalian', compact('borrowing'))
                  ->setPaper('A4', 'portrait'); 

        return $pdf->stream("Struk-Pengembalian-{$borrowing->id}.pdf");
    }

    public function history()
    {
        $history = Borrowing::with(['borrowingDetails.tool', 'user'])->get();

        $pdf = Pdf::loadView('pages.report.print', compact('history'))
                  ->setPaper('A4', 'landscape');

        return $pdf->stream("Riwayat Peminjaman.pdf");
    }

    /**
     * Halaman laporan dengan filter
     */


public function index(Request $request)
{
    $query = Borrowing::with(['borrowingDetails.tool.category', 'user'])->where('status', 'selesai'); 

    // Filter tanggal
    if ($request->start_date && $request->end_date) {
        $query->whereBetween('tanggal_pinjam', [$request->start_date, $request->end_date]);
    }

    // Filter status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // Filter kategori
    if ($request->category_id) {
        $query->whereHas('borrowingDetails.tool', function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });
    }

    $laporan = $query->latest()->get();
    $categories = Category::all(); 

    return view('pages.report.index', compact('laporan', 'categories'));
}


    public function exportPdf(Request $request)
    {
       $query = Borrowing::with(['borrowingDetails.tool', 'user']);

    // filter tanggal
    if ($request->start_date && $request->end_date) {
        $query->whereBetween('tanggal_pinjam', [$request->start_date, $request->end_date]);
    }

    // filter status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // filter kategori
    if ($request->category_id) {
        $query->whereHas('borrowingDetails.tool', function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });
    }

    $history = $query->latest()->get();


    $pdf = PDF::loadView('pages.report.print', compact('history'))
              ->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-peminjaman.pdf');
    }

    
}
