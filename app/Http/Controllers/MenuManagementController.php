<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuManagementController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::withCount('products')->get();

        return view('kelola-menu.kelola-menu', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,unavailable',
        ]);

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,unavailable',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Menu berhasil dihapus!');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255'
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Kategori ini masih memiliki produk di dalamnya.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}