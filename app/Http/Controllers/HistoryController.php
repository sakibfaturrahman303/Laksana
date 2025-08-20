<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with('borrowingDetails.tool')->where('status','selesai')->get();
        return view('pages.history.index', compact('borrowings'));
    }

        public function detail($id)
    {
        $borrowingDetails = Borrowing::with('borrowingDetails.tool')->findOrFail($id);
        return view('pages.history.toolHistory', compact('borrowingDetails'));
    }



}
