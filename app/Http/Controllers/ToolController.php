<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;

class ToolController extends Controller
{
public function index()
{
    $category = Category::orderBy('nama_kategori', 'asc')->get();

    $tools = Tool::with('category')
        ->join('categories', 'tools.category_id', '=', 'categories.id')
        ->orderBy('categories.nama_kategori', 'asc')
        ->orderBy('tools.merk', 'asc')
        ->orderBy('tools.nama_alat', 'asc')
        ->select('tools.*') // ambil hanya kolom tools supaya ga bentrok
        ->get();

    return view('pages.tools.index', compact('category', 'tools'));
}



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'merk' => 'required|string|max:100',
            'jumlah_total' => 'required|integer|min:0',
            'jumlah_tersedia' => 'required|integer|min:0|max:' . $request->jumlah_total,
            'category_id' => 'required|exists:categories,id',
        ]);


        Tool::create($validatedData);

        return redirect()->route('tools.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function show($id)
    {
        $tool = Tool::with('category')->findOrFail($id);
        return view('pages.tools.show', compact('tool'));
    }

    public function update(Request $request, $id)
    {
        $tool = Tool::findOrFail($id);

       $validatedData = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'merk' => 'required|string|max:100',
            'jumlah_total' => 'required|integer|min:0',
            'jumlah_tersedia' => 'required|integer|min:0|max:' . $request->jumlah_total,
            'category_id' => 'required|exists:categories,id',
        ]);


        $tool->update($validatedData);

        return redirect()->route('tools.index')->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tool = Tool::findOrFail($id);
        $tool->delete();

        return redirect()->route('tools.index')->with('success', 'Alat berhasil dihapus.');
    }
}
