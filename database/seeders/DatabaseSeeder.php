<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Kategori Dulu
        $catCoffee = Category::create([
            'name' => 'Coffee',
            'slug' => 'coffee',
            'icon' => 'coffee.png'
        ]);

        $catNonCoffee = Category::create([
            'name' => 'Non Coffee',
            'slug' => 'non-coffee',
            'icon' => 'tea.png'
        ]);

        $catSnack = Category::create([
            'name' => 'Snack',
            'slug' => 'snack',
            'icon' => 'cookie.png'
        ]);

        // 2. Buat Produk - Kategori Coffee
        Product::create([
            'category_id' => $catCoffee->id,
            'name' => 'Kopi Susu Gula Aren',
            'description' => 'Espresso, Susu Fresh Milk, dan Gula Aren Asli',
            'price' => 18000,
            'stock' => 100,
            'image' => 'kopsus.jpg', 
            'is_available' => true,
        ]);

        Product::create([
            'category_id' => $catCoffee->id,
            'name' => 'Americano',
            'description' => 'Espresso shot dengan air panas',
            'price' => 15000,
            'stock' => 50,
            'image' => 'americano.jpg',
            'is_available' => true,
        ]);

        // 3. Buat Produk - Kategori Snack
        Product::create([
            'category_id' => $catSnack->id,
            'name' => 'Croissant Butter',
            'description' => 'Renyah di luar, lembut di dalam',
            'price' => 22000,
            'stock' => 20,
            'image' => 'croissant.jpg',
            'is_available' => true,
        ]);
        
        // 4. Buat User (Kasir) - Optional
        User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir@kopi.com',
            'password' => bcrypt('password'),
        ]);
    }
}

