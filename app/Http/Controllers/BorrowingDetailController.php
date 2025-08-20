<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingDetailController extends Controller
{
    public function show($id)
    {
        $borrowingDetails = Borrowing::with('borrowingDetails.tool')->findOrFail($id);
        return view('pages.borrowing.show', compact('borrowingDetails'));
    }

}
