<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->where('is_available', true)->get();
        return view('pos.index', compact('categories', 'products'));
    }

    // --- TAMBAHAN BARU ---
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'cart' => 'required|array', // Harus ada data keranjang
            'total_price' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        try {
            // 2. Gunakan Database Transaction (Biar aman)
            DB::beginTransaction();

            // A. Buat Order Header
            $order = Order::create([
                'transaction_code' => 'TRX-' . time() . '-' . rand(100, 999), // Kode Unik: TRX-172345-123
                'customer_name' => 'Pelanggan Umum', // Bisa diganti input nanti
                'payment_method' => $request->payment_method,
                'status' => 'paid', // Anggap langsung lunas
                'total_price' => $request->total_price,
                'paid_amount' => $request->total_price, // Asumsi uang pas dulu
                'change_amount' => 0,
            ]);

            // B. Masukkan Detail Item
            foreach ($request->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'], // Simpan nama saat ini
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'sub_total' => $item['price'] * $item['qty'],
                ]);
            }

            // C. Simpan Permanen
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Berhasil!',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            // Jika error, batalkan semua perubahan database
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }


    // Menampilkan Halaman Struk
    public function print($id)
    {
        // Ambil data order beserta item-nya
        $order = Order::with('items')->findOrFail($id);
        
        return view('pos.print', compact('order'));
    }

}
