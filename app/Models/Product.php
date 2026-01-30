<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi (mass assignment)
    protected $guarded = ['id'];

    // INI YANG KURANG TADI:
    // Memberitahu Laravel bahwa Produk ini "Milik" (Belongs To) satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

