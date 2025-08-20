<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\BorrowingDetailController;

// Route::get('/', function () {
//     return view('layouts.app');
// });

Auth::routes();
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// wajib login
Route::group(['middleware' => 'auth'], function () {
   Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard'); 
   
    //    hanya admin yang bisa akses
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('category', CategoryController::class)
        ->except(['create', 'edit']);

        Route::resource('users', UserController::class)
        ->except(['create','show', 'edit']);
        Route::get('users/roles', [UserController::class, 'roles'])->name('users.roles');

    });

    // semua user bisa akses
    Route::resource('tools', ToolController::class)
        ->except(['create', 'edit']);

    Route::get('borrowing', [BorrowingController::class, 'index'])->name('borrowing.index');
    Route::get('borrowing/create', [BorrowingController::class, 'create'])->name('borrowing.create');
    Route::post('borrowing/store', [BorrowingController::class, 'store'])->name('borrowing.store');
    Route::post('borrowing/{id}/return', [BorrowingController::class, 'returnTool'])->name('borrowing.return');
    Route::get('borrowing/{id}/show', [BorrowingDetailController::class, 'show'])->name('borrowing.show');

    Route::get('borrowing/{id}/edit', [BorrowingController::class, 'edit'])->name('borrowing.edit');
    Route::post('borrowing/{id}', [BorrowingController::class, 'update'])->name('borrowing.update');
    Route::delete('borrowing/{id}', [BorrowingController::class, 'destroy'])->name('borrowing.destroy');

    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::get('report/export', [ReportController::class, 'exportPdf'])->name('report.export');

    Route::get('/borrowing/{id}/strukPeminjaman', [ReportController::class, 'cetakPeminjaman'])->name('borrowing.strukPeminjaman');
    Route::get('/borrowing/{id}/strukPengembalian', [ReportController::class, 'cetakPengembalian'])->name('borrowing.strukPengembalian');
    Route::get('history/report', [ReportController::class, 'history'])->name('history.report');

     Route::get('history', [HistoryController::class, 'index'])->name('history.index');
     Route::get('history/{id}/detail', [HistoryController::class, 'detail'])->name('history.detail');

});



