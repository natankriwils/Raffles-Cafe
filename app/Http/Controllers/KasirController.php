<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        $allIngredients = Ingredient::all()->keyBy('name');

        $recipes = [
            'Americano' => ['Gallon Water', 'Espresso Coffee Beans', 'Plastic Cup Ice 16oz'],
            'Coffee Latte' => ['Espresso Coffee Beans', 'Fresh Milk', 'Plastic Cup Ice 16oz'],
            'Caramel Latte' => ['Espresso Coffee Beans', 'Fresh Milk', 'Caramel Syrup', 'Plastic Cup Ice 16oz'],
            'Chocolate' => ['Chocolate Powder', 'Gallon Water', 'Plastic Cup Ice 16oz'],
            'Matcha Latte' => ['Matcha Powder', 'Fresh Milk', 'Plastic Cup Ice 16oz'],
            'Croissant' => ['Croissant'],
            'Beef Toast Bread' => ['Beed Toast Bread'],
            'Cheesecake' => ['Cheesecake'],
            'Red Velvet Cake' => ['Red Velvet Cake']
        ];

        $products = Product::with(['category'])->get()->map(function($p) use ($recipes, $allIngredients) {
            $isAvailable = true;
            $stockText = 'Ready';

            if (isset($recipes[$p->name])) {
                $requiredIngredients = $recipes[$p->name];
                
                foreach ($requiredIngredients as $ingName) {
                    $ingredient = $allIngredients->get($ingName);
                    
                    if (!$ingredient || $ingredient->stock <= 0) {
                        $isAvailable = false;
                        $stockText = 'Habis: ' . $ingName;
                        break;
                    }
                }
            } else {
                $isAvailable = true;
            }

            return [
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'base_price' => (int) $p->base_price,
                'category_slug' => $p->category ? strtolower($p->category->slug) : 'other',
                'category_name' => $p->category ? $p->category->name : 'Other',
                'is_available' => $isAvailable,
                'stock_text' => $stockText,
            ];
        });

        $categories = Category::all();

        return view('kasir.kasir', compact('products', 'categories'));
    }
}