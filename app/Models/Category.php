<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Memberitahu Laravel bahwa Kategori ini "Punya Banyak" (Has Many) Produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

