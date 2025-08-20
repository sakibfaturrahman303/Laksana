<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Tool;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Models\BorrowingDetail;

class BorrowingController extends Controller
{
   public function index()
    {
        $borrowings = Borrowing::with('borrowingDetails.tool')->where('status', '!=', 'selesai')->get();
        return view('pages.borrowing.index', compact('borrowings'));
    }

     public function create()
    {
        $tools = Tool::where('jumlah_tersedia', '>', 0)->get();
        return view('pages.borrowing.create', compact('tools'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date',
            'keperluan' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            
            // vaalidasi untuk tools yang dipinjam
           'tools' => 'required|array|min:1', // Harus ada minimal 1 alat
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.jumlah_pinjam' => 'required|integer|min:1',
        ]);

        
        DB::beginTransaction();
        try {
            $borrowing = Borrowing::create([
                'user_id' => auth()->id(),
                'nama_peminjam' => $validatedData['nama_peminjam'],
                'tanggal_pinjam' => $validatedData['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $validatedData['tanggal_kembali_rencana'],
                'keperluan' => $validatedData['keperluan'],
                'keterangan' => $validatedData['keterangan'] ?? null,
                'status' => 'dipinjam',
            ]);

            // siimpan detail peminjaman untuk setiap alat yang dipinjam
            foreach ($validatedData['tools'] as $toolData) {
                $tool = Tool::findOrFail($toolData['tool_id']);
                // peeriksa apakah jumlah yang diminta melebihi jumlah yang tersedia
                if ($tool->jumlah_tersedia < $toolData['jumlah_pinjam']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Jumlah {$tool->nama_alat} yang diminta melebihi jumlah yang tersedia.");

                }

                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'tool_id' => $tool->id,
                    'jumlah_pinjam' => $toolData['jumlah_pinjam'],
                ]);

                // kurangi jumlah tersedia alat
                 $tool->decrement('jumlah_tersedia', $toolData['jumlah_pinjam']);
            }

            DB::commit();

            return redirect()->route('borrowing.index')->with('success', 'Peminjaman berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
           return redirect()->back()->with('error', $e->getMessage());

        }
    }

    public function returnTool(Request $request, $id)
    {

        $validatedData = $request->validate([
                    'kondisi' => 'required|string|max:255',
         ]);
        
    $borrowing = Borrowing::with('borrowingDetails')->find($id);

    if (!$borrowing) {
        return redirect()->route('borrowing.index')->with('error', 'Peminjaman tidak ditemukan.');
    }

    // Cek apakah sudah selesai
    if ($borrowing->status === 'selesai') {
        return redirect()->route('borrowing.index')->with('error', 'Peminjaman sudah dikembalikan.');
    }

    DB::beginTransaction();
    try {
        // Update status dan tanggal kembali aktual
        $borrowing->update([
            'status' => 'selesai',
            'kondisi' => $validatedData['kondisi'],
            'tanggal_kembali_aktual' => now(),
        ]);

        // Kembalikan jumlah alat yang dipinjam
        foreach ($borrowing->borrowingDetails as $detail) {
            if ($detail->tool) { // pastikan relasi ada
                $detail->tool->increment('jumlah_tersedia', $detail->jumlah_pinjam);
            }
        }

        DB::commit();
        return redirect()->back()->with('success', 'Peminjaman berhasil dikembalikan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
    }

public function edit($id)
{
    $borrowing = Borrowing::with(['borrowingDetails.tool'])->findOrFail($id);

    // ID alat yang sudah ada di borrowing ini (agar tetap muncul meski stok 0)
    $currentToolIds = $borrowing->borrowingDetails->pluck('tool_id')->unique();

    // Tampilkan alat yang stoknya > 0 ATAU alat yang sudah dipinjam pada transaksi ini
    $tools = Tool::where('jumlah_tersedia', '>', 0)
        ->orWhereIn('id', $currentToolIds)
        ->orderBy('nama_alat')
        ->get();

    // Data awal untuk tabel (dipakai di @json($alatDipilih) pada Blade)
    $alatDipilih = $borrowing->borrowingDetails->map(function ($detail) {
        $tool = $detail->tool;
        return [
            'id'       => $tool->id,
            'kode'     => $tool->kode_alat,
            'nama'     => $tool->nama_alat,
            'merk'     => $tool->merk,
            // stok dikembalikan dulu sebesar yang sedang dipinjam agar user bisa edit jumlahnya
            'tersedia' => (int) $tool->jumlah_tersedia + (int) $detail->jumlah_pinjam,
            'jumlah'   => (int) $detail->jumlah_pinjam,
        ];
    })->values();

    return view('pages.borrowing.edit', compact('borrowing', 'tools', 'alatDipilih'));
}


public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'nama_peminjam' => 'required|string|max:255',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali_rencana' => 'required|date',
        'keperluan' => 'required|string|max:255',
        'keterangan' => 'nullable|string|max:255',

        'tools' => 'required|array|min:1',
        'tools.*.tool_id' => 'required|exists:tools,id',
        'tools.*.jumlah_pinjam' => 'required|integer|min:1',
    ]);

    $borrowing = Borrowing::with('borrowingDetails')->findOrFail($id);

    DB::beginTransaction();
    try {
        // Kembalikan dulu stok alat lama
        foreach ($borrowing->borrowingDetails as $detail) {
            if ($detail->tool) {
                $detail->tool->increment('jumlah_tersedia', $detail->jumlah_pinjam);
            }
            $detail->delete();
        }

        // Update borrowing
        $borrowing->update([
            'nama_peminjam' => $validatedData['nama_peminjam'],
            'tanggal_pinjam' => $validatedData['tanggal_pinjam'],
            'tanggal_kembali_rencana' => $validatedData['tanggal_kembali_rencana'],
            'keperluan' => $validatedData['keperluan'],
            'keterangan' => $validatedData['keterangan'] ?? null,
        ]);

        // Tambahkan detail baru
        foreach ($validatedData['tools'] as $toolData) {
            $tool = Tool::findOrFail($toolData['tool_id']);

            if ($tool->jumlah_tersedia < $toolData['jumlah_pinjam']) {
                DB::rollBack();
                return redirect()->back()->with('error', "Jumlah {$tool->nama_alat} yang diminta melebihi stok tersedia.");
            }

            BorrowingDetail::create([
                'borrowing_id' => $borrowing->id,
                'tool_id' => $tool->id,
                'jumlah_pinjam' => $toolData['jumlah_pinjam'],
            ]);

            $tool->decrement('jumlah_tersedia', $toolData['jumlah_pinjam']);
        }

        DB::commit();
        return redirect()->route('borrowing.index')->with('success', 'Peminjaman berhasil diperbarui.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function destroy($id)
{
    $borrowing = Borrowing::with('borrowingDetails')->findOrFail($id);

    DB::beginTransaction();
    try {
        // Kembalikan stok alat
        foreach ($borrowing->borrowingDetails as $detail) {
            if ($detail->tool) {
                $detail->tool->increment('jumlah_tersedia', $detail->jumlah_pinjam);
            }
            $detail->delete();
        }

        $borrowing->delete();

        DB::commit();
        return redirect()->route('borrowing.index')->with('success', 'Peminjaman berhasil dihapus.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
