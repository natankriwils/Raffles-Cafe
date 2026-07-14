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
            ['name' => 'Fresh Milk Greenfields', 'stock' => 15, 'unit' => 'Box (1L)', 'min_stock' => 5],
            ['name' => 'Oat Milk Barista', 'stock' => 6, 'unit' => 'Box (1L)', 'min_stock' => 2],
            
            ['name' => 'Matcha Powder Premium', 'stock' => 4, 'unit' => 'Pack (500g)', 'min_stock' => 1],
            ['name' => 'Dark Chocolate Powder', 'stock' => 5, 'unit' => 'Pack (1kg)', 'min_stock' => 1],
            ['name' => 'Earl Grey Tea', 'stock' => 10, 'unit' => 'Box (25s)', 'min_stock' => 2],
            
            ['name' => 'Caramel Syrup Monin', 'stock' => 3, 'unit' => 'Bottle (750ml)', 'min_stock' => 1],
            ['name' => 'Vanilla Syrup Monin', 'stock' => 3, 'unit' => 'Bottle (750ml)', 'min_stock' => 1],
            ['name' => 'Liquid Palm Sugar', 'stock' => 5, 'unit' => 'Jerrycan (1L)', 'min_stock' => 2],
            
            ['name' => 'Paper Cup Hot 12oz', 'stock' => 10, 'unit' => 'Sleeve (50 pcs)', 'min_stock' => 2],
            ['name' => 'Plastic Cup Ice 16oz', 'stock' => 12, 'unit' => 'Sleeve (50 pcs)', 'min_stock' => 2],
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