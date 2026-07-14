<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::latest()->get();
        return view('kelola-stok.kelola-stok', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
        ]);

        Ingredient::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'stock' => $request->stock,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock,
        ]);

        return redirect()->back()->with('success', 'New ingredient added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
        ]);

        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'stock' => $request->stock,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock,
        ]);

        return redirect()->back()->with('success', 'Stock updated successfully!');
    }

    public function destroy($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        return redirect()->back()->with('success', 'Ingredient removed successfully!');
    }
}