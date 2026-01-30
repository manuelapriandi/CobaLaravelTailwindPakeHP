<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi
    protected $guarded = ['id'];
    
    // Relasi (Opsional, tapi bagus ada)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

