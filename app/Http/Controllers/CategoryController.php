<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   public function index()
    {
       $category = Category::all(); 
        return view('pages.category.index',compact('category'));
    }

    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        Category::create($validatedData);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Logic to update an existing category
        $validatedData = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validatedData);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function show($id)
    {
        // Logic to show a specific category
        $category = Category::findOrFail($id);
        return view('pages.category.show', compact('category'));
    }

    public function destroy($id)
    {
        // Logic to delete a category
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
