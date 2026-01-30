<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;

// Arahkan halaman utama ('/') langsung ke PosController
Route::get('/', [PosController::class, 'index'])->name('pos.index');

