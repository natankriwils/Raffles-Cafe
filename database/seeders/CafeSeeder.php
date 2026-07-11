<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CafeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('categories')->truncate();
        DB::table('products')->truncate();
        DB::table('shifts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Manajer Cafe']);
        $cashierRole = Role::create(['name' => 'kasir', 'display_name' => 'Kasir Utama']);

        User::create([
            'role_id' => $adminRole->id,
            'name' => 'Natan Admin',
            'email' => 'admin@rafflescafe.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        User::create([
            'role_id' => $cashierRole->id,
            'name' => 'Nathan Admin',
            'email' => 'kasir@rafflescafe.com',
            'password' => Hash::make('kasir123'),
            'is_active' => true,
        ]);

        DB::table('shifts')->insert([
            'id' => 1,
            'user_id' => 1, 
            'start_time' => now(),
            'end_time' => null,
            'starting_cash' => 500000.00, 
            'ending_cash' => null,
            'difference' => null,
            'status' => 'open',
            'notes' => 'Shift pagi dimulai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $coffee = Category::create(['name' => 'Coffee', 'slug' => 'coffee', 'is_active' => true]);
        $nonCoffee = Category::create(['name' => 'Non-Coffee', 'slug' => 'non-coffee', 'is_active' => true]);
        $pastry = Category::create(['name' => 'Pastry', 'slug' => 'pastry', 'is_active' => true]);
        $toast = Category::create(['name' => 'Toast', 'slug' => 'toast', 'is_active' => true]);
        $matcha = Category::create(['name' => 'Matcha', 'slug' => 'matcha', 'is_active' => true]);

        Product::create([
            'category_id' => $coffee->id,
            'name' => 'Americano',
            'slug' => 'americano',
            'base_price' => 18000,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $coffee->id,
            'name' => 'Coffee Latte',
            'slug' => 'coffee-latte',
            'base_price' => 22000,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $coffee->id,
            'name' => 'Caramel Latte',
            'slug' => 'caramel-latte',
            'base_price' => 24000,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $nonCoffee->id,
            'name' => 'Chocolate',
            'slug' => 'chocolate',
            'base_price' => 20000,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $matcha->id,
            'name' => 'Matcha Latte',
            'slug' => 'matcha-latte',
            'base_price' => 22000,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $pastry->id,
            'name' => 'Croissant',
            'slug' => 'croissant',
            'base_price' => 25000,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $toast->id,
            'name' => 'Beef Toast Bread',
            'slug' => 'beef-toast-bread',
            'base_price' => 25000,
            'is_active' => true,
        ]);
    }
}