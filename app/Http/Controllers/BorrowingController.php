<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Models\BorrowingDetail;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with('borrowingDetails.tool')
            ->where('status', 'dipinjam')
            ->get();

        return view('pages.borrowing.index', compact('borrowings'));
    }

   public function create()
    {
         $category = Category::orderBy('nama_kategori', 'asc')->get();

        $tools = Tool::with('category')
            ->join('categories', 'tools.category_id', '=', 'categories.id')
            ->orderBy('categories.nama_kategori', 'asc')
            ->orderBy('tools.merk', 'asc')
            ->orderBy('tools.nama_alat', 'asc')
            ->select('tools.*') // ambil hanya kolom tools supaya ga bentrok
            ->get();

        return view('pages.borrowing.create', compact('tools','category'));
    }


    public function store(Request $request)
{
    // Validasi dasar
    $validatedData = $request->validate([
        'nama_peminjam' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
        'tanggal_pinjam' => 'required|date|after_or_equal:today',
        'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
        'keperluan' => 'required|string|max:500|min:5',

        'tools' => 'required|array|min:1|max:10', // Maksimal 10 alat per peminjaman
        'tools.*.tool_id' => 'required|exists:tools,id',
        'tools.*.jumlah_pinjam' => 'required|integer|min:1|max:100', // Maksimal 100 per item
        'tools.*.kondisi_awal' => 'required|string|max:255|',
        'tools.*.keterangan_awal' => 'nullable|string|max:500',
    ], [
        // Custom error messages
        'nama_peminjam.required' => 'Nama peminjam wajib diisi.',
        'nama_peminjam.regex' => 'Nama peminjam hanya boleh berisi huruf dan spasi.',
        'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
        'tanggal_kembali_rencana.after' => 'Tanggal kembali rencana harus setelah tanggal pinjam.',
        'keperluan.min' => 'Keperluan minimal 5 karakter.',
        'tools.min' => 'Minimal harus meminjam 1 alat.',
        'tools.max' => 'Maksimal 10 alat per peminjaman.',
        'tools.*.jumlah_pinjam.max' => 'Jumlah maksimal per alat adalah 100.',
        'tools.*.kondisi_awal.in' => 'Kondisi awal harus salah satu: Baik, Rusak Ringan, Rusak Berat.',
    ]);

    // Validasi tambahan: Cek duplikasi tool_id
    $toolIds = collect($validatedData['tools'])->pluck('tool_id');
    if ($toolIds->count() !== $toolIds->unique()->count()) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Tidak boleh meminjam alat yang sama lebih dari sekali dalam satu peminjaman.');
    }

    // Validasi rentang tanggal (maksimal 30 hari)
    $tanggalPinjam = Carbon::parse($validatedData['tanggal_pinjam']);
    $tanggalKembali = Carbon::parse($validatedData['tanggal_kembali_rencana']);
    $selisihHari = $tanggalPinjam->diffInDays($tanggalKembali);
    
    if ($selisihHari > 30) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Durasi peminjaman tidak boleh lebih dari 30 hari.');
    }

    DB::beginTransaction();
    try {
        // Validasi ketersediaan stok untuk setiap alat
        $stockErrors = [];
        $totalJumlahPerTool = [];

        // Hitung total jumlah yang dipinjam per tool (jika ada duplikasi yang lolos validasi awal)
        foreach ($validatedData['tools'] as $index => $toolData) {
            $toolId = $toolData['tool_id'];
            if (!isset($totalJumlahPerTool[$toolId])) {
                $totalJumlahPerTool[$toolId] = 0;
            }
            $totalJumlahPerTool[$toolId] += $toolData['jumlah_pinjam'];
        }

        // Cek ketersediaan stok
        foreach ($totalJumlahPerTool as $toolId => $totalJumlah) {
            $tool = Tool::findOrFail($toolId);
            
            // Cek apakah tool aktif
            if (isset($tool->status) && $tool->status === 'inactive') {
                $stockErrors[] = "Alat {$tool->nama_alat} sedang tidak aktif.";
                continue;
            }

            if ($tool->jumlah_tersedia < $totalJumlah) {
                $stockErrors[] = "Stok {$tool->nama_alat} tidak mencukupi. Tersedia: {$tool->jumlah_tersedia}, Diminta: {$totalJumlah}";
            }
        }

        if (!empty($stockErrors)) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kesalahan stok: ' . implode(' | ', $stockErrors));
        }

        // Buat record peminjaman
        $borrowing = Borrowing::create([
            'operator_pinjam' => auth()->id(),
            'operator_kembali' => null,
            'nama_peminjam' => trim($validatedData['nama_peminjam']),
            'tanggal_pinjam' => $validatedData['tanggal_pinjam'],
            'tanggal_kembali_rencana' => $validatedData['tanggal_kembali_rencana'],
            'keperluan' => trim($validatedData['keperluan']),
            'status' => 'dipinjam',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat detail peminjaman dan update stok
        foreach ($validatedData['tools'] as $toolData) {
            $tool = Tool::findOrFail($toolData['tool_id']);

            BorrowingDetail::create([
                'borrowing_id' => $borrowing->id,
                'tool_id' => $tool->id,
                'jumlah_pinjam' => $toolData['jumlah_pinjam'],
                'kondisi_awal' => trim($toolData['kondisi_awal']),
                'keterangan_awal' => isset($toolData['keterangan_awal']) ? trim($toolData['keterangan_awal']) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update stok tersedia
            $tool->decrement('jumlah_tersedia', $toolData['jumlah_pinjam']);
        }

        DB::commit();

        // Log aktivitas (opsional)
        \Log::info('Peminjaman baru dibuat', [
            'borrowing_id' => $borrowing->id,
            'operator_id' => auth()->id(),
            'nama_peminjam' => $validatedData['nama_peminjam'],
            'jumlah_alat' => count($validatedData['tools'])
        ]);

        return redirect()->route('borrowing.index')->with('success', 'Peminjaman berhasil disimpan.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        // Log error
        \Log::error('Error saat membuat peminjaman', [
            'error' => $e->getMessage(),
            'data' => $request->all()
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
    }
}

  public function returnTool(Request $request, $id)
    {
        $borrowing = Borrowing::with('borrowingDetails.tool')->find($id);

        if (!$borrowing) {
            return redirect()->route('borrowing.index')->with('error', 'Peminjaman tidak ditemukan.');
        }

        if (in_array($borrowing->status, ['selesai', 'terlambat'])) {
            return redirect()->route('borrowing.index')->with('error', 'Peminjaman sudah dikembalikan.');
        }

        $validatedData = $request->validate([
            'details' => 'required|array',
            'details.*.kondisi_akhir' => 'required|string|max:255',
            'details.*.keterangan_akhir' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $tanggalKembaliAktual = Carbon::now();
            $tanggalPinjam = $borrowing->tanggal_pinjam ? Carbon::parse($borrowing->tanggal_pinjam) : null;
            $tanggalKembaliRencana = $borrowing->tanggal_kembali_rencana ? Carbon::parse($borrowing->tanggal_kembali_rencana) : null;

            // Validasi tanggal kembali tidak boleh sebelum tanggal pinjam
            if ($tanggalPinjam && $tanggalKembaliAktual->lt($tanggalPinjam)) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Tanggal kembali tidak boleh sebelum tanggal pinjam.');
            }

            // Tentukan status berdasarkan ketentuan:
            // Status 'terlambat' jika dan hanya jika tanggal_kembali_aktual > tanggal_kembali_rencana
            // Status 'selesai' untuk semua kondisi lainnya
           // Tentukan status berdasarkan tanggal (bukan jam)
            $status = 'selesai'; // default status

            if ($tanggalKembaliRencana && $tanggalKembaliAktual->gt($tanggalKembaliRencana->endOfDay())) {
                $status = 'terlambat';
            }


            // Update detail peminjaman
            foreach ($validatedData['details'] as $detailId => $data) {
                $detail = $borrowing->borrowingDetails->where('id', $detailId)->first();
                if (!$detail) continue;

                $detail->update([
                    'kondisi_akhir' => $data['kondisi_akhir'],
                    'keterangan_akhir' => $data['keterangan_akhir'] ?? null,
                ]);

                // Tambah kembali stok alat jika tidak hilang
                if ($detail->tool && $data['kondisi_akhir'] !== 'Hilang') {
                    $detail->tool->increment('jumlah_tersedia', $detail->jumlah_pinjam);
                }
            }

            // Update data peminjaman
            $borrowing->update([
                'status' => $status,
                'tanggal_kembali_aktual' => $tanggalKembaliAktual->format('Y-m-d H:i:s'),
                'catatan' => $validatedData['catatan'] ?? null,
                'operator_kembali' => auth()->id(),
            ]);

            DB::commit();
            
            $statusMessage = $status === 'terlambat' ? ' (TERLAMBAT)' : '';
            return redirect()->route('history.index')->with(
                'success',
                'Peminjaman berhasil dikembalikan' . $statusMessage . '.'
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $borrowing = Borrowing::with(['borrowingDetails.tool'])->findOrFail($id);

        $currentToolIds = $borrowing->borrowingDetails->pluck('tool_id')->unique();

        $tools = Tool::where('jumlah_tersedia', '>', 0)
            ->orWhereIn('id', $currentToolIds)
            ->orderBy('nama_alat')
            ->get();

        $alatDipilih = $borrowing->borrowingDetails->map(function ($detail) {
            $tool = $detail->tool;
            return [
                'id'       => $tool->id,
                'kode'     => $tool->kode_alat,
                'nama'     => $tool->nama_alat,
                'merk'     => $tool->merk,
                'tersedia' => (int) $tool->jumlah_tersedia + (int) $detail->jumlah_pinjam,
                'jumlah'   => (int) $detail->jumlah_pinjam,
                'keterangan_awal' => $detail->keterangan_awal,
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

            'tools' => 'required|array|min:1',
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.jumlah_pinjam' => 'required|integer|min:1',
            'tools.*.keterangan_awal' => 'nullable|string|max:255',
        ]);

        $borrowing = Borrowing::with('borrowingDetails')->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($borrowing->borrowingDetails as $detail) {
                if ($detail->tool) {
                    $detail->tool->increment('jumlah_tersedia', $detail->jumlah_pinjam);
                }
                $detail->delete();
            }

            $borrowing->update([
                'nama_peminjam' => $validatedData['nama_peminjam'],
                'tanggal_pinjam' => $validatedData['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $validatedData['tanggal_kembali_rencana'],
                'keperluan' => $validatedData['keperluan'],
            ]);

            foreach ($validatedData['tools'] as $toolData) {
                $tool = Tool::findOrFail($toolData['tool_id']);

                if ($tool->jumlah_tersedia < $toolData['jumlah_pinjam']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Jumlah {$tool->nama_alat} melebihi stok tersedia.");
                }

                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'tool_id' => $tool->id,
                    'jumlah_pinjam' => $toolData['jumlah_pinjam'],
                    'keterangan_awal' => $toolData['keterangan_awal'] ?? null,
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
