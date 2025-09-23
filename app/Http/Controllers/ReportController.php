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

    public function index(Request $request)
{
    $query = Borrowing::with(['operatorPinjam', 'operatorKembali'])
        ->whereIn('status', ['selesai', 'terlambat']);

    // Filter tanggal
    if ($request->start_date) {
        $query->where('tanggal_pinjam', '>=', $request->start_date);
    }
    if ($request->end_date) {
        $query->where('tanggal_pinjam', '<=', $request->end_date);
    }

    // Filter kategori
    if ($request->kategori) {
        $kategori = $request->kategori;
        $query->whereHas('borrowingDetails.tool.category', function ($q) use ($kategori) {
            $q->where('nama_kategori', $kategori);
        });
    }

    // Filter alat
    if ($request->alat) {
        $alat = $request->alat;
        $query->whereHas('borrowingDetails.tool', function ($q) use ($alat) {
            $q->where('nama_alat', $alat);
        });
    }

    // ✅ Batasi relasi borrowingDetails sesuai filter
    $laporan = $query->with([
            'borrowingDetails' => function ($q) use ($request) {
                if ($request->kategori) {
                    $kategori = $request->kategori;
                    $q->whereHas('tool.category', function ($qq) use ($kategori) {
                        $qq->where('nama_kategori', $kategori);
                    });
                }
                if ($request->alat) {
                    $alat = $request->alat;
                    $q->whereHas('tool', function ($qq) use ($alat) {
                        $qq->where('nama_alat', $alat);
                    });
                }
            },
            'borrowingDetails.tool.category',
            'operatorPinjam',
            'operatorKembali'
        ])
        ->latest('tanggal_pinjam')
        ->get();

    $categories = Category::orderBy('nama_kategori')->get();
    $tools = Tool::with('category')->orderBy('nama_alat')->get();

    return view('pages.report.index', compact('laporan', 'categories', 'tools'));
}

public function exportPdf(Request $request)
{
    $query = Borrowing::with(['operatorPinjam', 'operatorKembali'])
        ->whereIn('status', ['selesai', 'terlambat']);

    // Filter tanggal
    if ($request->start_date) {
        $query->where('tanggal_pinjam', '>=', $request->start_date);
    }
    if ($request->end_date) {
        $query->where('tanggal_pinjam', '<=', $request->end_date);
    }

    // Filter kategori
    if ($request->kategori) {
        $kategori = $request->kategori;
        $query->whereHas('borrowingDetails.tool.category', function ($q) use ($kategori) {
            $q->where('nama_kategori', $kategori);
        });
    }

    // Filter alat
    if ($request->alat) {
        $alat = $request->alat;
        $query->whereHas('borrowingDetails.tool', function ($q) use ($alat) {
            $q->where('nama_alat', $alat);
        });
    }

    // ✅ Batasi relasi borrowingDetails sesuai filter
    $history = $query->with([
            'borrowingDetails' => function ($q) use ($request) {
                if ($request->kategori) {
                    $kategori = $request->kategori;
                    $q->whereHas('tool.category', function ($qq) use ($kategori) {
                        $qq->where('nama_kategori', $kategori);
                    });
                }
                if ($request->alat) {
                    $alat = $request->alat;
                    $q->whereHas('tool', function ($qq) use ($alat) {
                        $qq->where('nama_alat', $alat);
                    });
                }
            },
            'borrowingDetails.tool.category',
            'operatorPinjam',
            'operatorKembali'
        ])
        ->latest('tanggal_pinjam')
        ->get();

    $pdf = Pdf::loadView('pages.report.print', compact('history'))
              ->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-peminjaman.pdf');
}

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

 


}
