<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;

// Arahkan halaman utama ('/') langsung ke PosController
Route::get('/', [PosController::class, 'index'])->name('pos.index');
Route::post('/transaction', [PosController::class, 'store'])->name('pos.store');
// Tambahkan di bawah route transaction
Route::get('/order/{id}/print', [PosController::class, 'print'])->name('pos.print');
