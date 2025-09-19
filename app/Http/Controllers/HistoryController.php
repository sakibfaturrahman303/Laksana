<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    /**
     * Display a listing of all completed borrowing records.
     *
     * @return \Illuminate\View\View
     */
   public function index()
    {
        $borrowings = Borrowing::with('borrowingDetails.tool')->whereIn('status', ['selesai', 'terlambat'])->get();
        return view('pages.history.index', compact('borrowings'));
    }


    /**
     * Display the details for a specific borrowing record.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $borrowingDetails = Borrowing::with('borrowingDetails.tool')
            ->findOrFail($id);

        return view('pages.history.toolHistory', compact('borrowingDetails'));
    }
}