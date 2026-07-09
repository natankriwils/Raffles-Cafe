<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Modifier;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CafeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles (Gunakan firstOrCreate agar tidak bentrok UNIQUE)
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Manajer Cafe']
        );

        $cashierRole = Role::firstOrCreate(
            ['name' => 'kasir'],
            ['display_name' => 'Kasir Utama']
        );

        // 2. Seed Users / Employees
        User::firstOrCreate(
            ['email' => 'admin@rafflescafe.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Natan Admin',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        $kasir = User::firstOrCreate(
            ['email' => 'kasir@rafflescafe.com'],
            [
                'role_id' => $cashierRole->id,
                'name' => 'Budi Kasir',
                'password' => Hash::make('kasir123'),
                'is_active' => true,
            ]
        );

        // Seed Shift (Gunakan cek manual agar tidak duplikat di SQLite)
        $checkShift = \Illuminate\Support\Facades\DB::table('shifts')->where('id', 1)->exists();
        if (!$checkShift) {
            \Illuminate\Support\Facades\DB::table('shifts')->insert([
                'id' => 1,
                'name' => 'Sore',
                'start_time' => '15:00:00',
                'end_time' => '23:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Berikan nilai 'is_active' => true pada kategori agar lolos filter di KasirController
        $coffee = Category::create(['name' => 'Coffee', 'slug' => 'coffee', 'is_active' => true]);
        $nonCoffee = Category::create(['name' => 'Non-Coffee', 'slug' => 'non-coffee', 'is_active' => true]);
        $pastry = Category::create(['name' => 'Pastry', 'slug' => 'pastry', 'is_active' => true]);

        $extraShot = Modifier::create(['name' => 'Extra Shot Espresso', 'price' => 5000]);
        $oatside = Modifier::create(['name' => 'Oatside Milk Substitute', 'price' => 8000]);
        $jelly = Modifier::create(['name' => 'Coffee Jelly', 'price' => 4000]);

        $kopiAren = Product::create([
            'category_id' => $coffee->id,
            'name' => 'Es Kopi Susu Gula Aren',
            'slug' => 'es-kopi-susu-gula-aren',
            'description' => 'Espresso blend dengan susu segar dan sirup aren murni.',
            'base_price' => 18000,
            'is_active' => true,
        ]);

        Variant::create([
            'product_id' => $kopiAren->id,
            'name' => 'Regular',
            'additional_price' => 0,
        ]);

        Variant::create([
            'product_id' => $kopiAren->id,
            'name' => 'Large',
            'additional_price' => 5000,
        ]);

        // Produk 2: Matcha Latte (Punya Varian)
        $matcha = Product::create([
            'category_id' => $nonCoffee->id,
            'name' => 'Matcha Latte',
            'slug' => 'matcha-latte',
            'description' => 'Pure Uji Matcha premium dengan susu vanilla.',
            'base_price' => 22000,
            'is_active' => true,
        ]);

        Variant::create([
            'product_id' => $matcha->id,
            'name' => 'Hot',
            'additional_price' => 0,
        ]);

        Variant::create([
            'product_id' => $matcha->id,
            'name' => 'Iced',
            'additional_price' => 2000,
        ]);

        // Produk 3: Croissant (Tanpa Varian Ukuran)
        Product::create([
            'category_id' => $pastry->id,
            'name' => 'Butter Croissant',
            'slug' => 'butter-croissant',
            'description' => 'Croissant renyah dengan mentega Prancis asli.',
            'base_price' => 25000,
            'is_active' => true,
        ]);
    }
}