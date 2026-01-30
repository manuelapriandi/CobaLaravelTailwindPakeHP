<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        // Ambil semua kategori
        $categories = Category::all();
        
        // Ambil semua produk yang tersedia (is_available = true)
        // Kita load juga relasi 'category'-nya biar gampang diambil namanya nanti
        $products = Product::with('category')->where('is_available', true)->get();

        // Kirim data ke view 'pos.index'
        return view('pos.index', compact('categories', 'products'));
    }
}

