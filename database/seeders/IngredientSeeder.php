<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Ingredient::truncate();

        Schema::enableForeignKeyConstraints();

        $ingredients = [
            ['name' => 'Espresso Coffee Beans', 'stock' => 8, 'unit' => 'Bag (1kg)', 'min_stock' => 2],
            ['name' => 'Fresh Milk', 'stock' => 15, 'unit' => 'Box (1L)', 'min_stock' => 5],
            ['name' => 'Gallon Water', 'stock' => 8, 'unit' => 'Gallon (18L)', 'min_stock' => 4],
            ['name' => 'Matcha Powder', 'stock' => 4, 'unit' => 'Pack (500g)', 'min_stock' => 1],
            ['name' => 'Chocolate Powder', 'stock' => 5, 'unit' => 'Pack (500g)', 'min_stock' => 1],
            ['name' => 'Caramel Syrup', 'stock' => 3, 'unit' => 'Bottle (750ml)', 'min_stock' => 1],
            ['name' => 'Plastic Cup Ice 16oz', 'stock' => 12, 'unit' => 'Sleeve (50 pcs)', 'min_stock' => 2],
            ['name' => 'Croissant', 'stock' => 10, 'unit' => 'pcs', 'min_stock' => 0],
            ['name' => 'Cheesecake', 'stock' => 10, 'unit' => 'pcs', 'min_stock' => 0],
            ['name' => 'Beed Toast Bread', 'stock' => 10, 'unit' => 'pcs', 'min_stock' => 0],
            ['name' => 'Red Velvet Cake', 'stock' => 10, 'unit' => 'pcs', 'min_stock' => 0]
        ];

        foreach ($ingredients as $item) {
            Ingredient::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'stock' => $item['stock'],
                'unit' => $item['unit'],
                'min_stock' => $item['min_stock'],
            ]);
        }
    }
}