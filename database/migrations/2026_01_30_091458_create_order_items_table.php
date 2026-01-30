<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        $table->foreignId('product_id')->constrained(); // Jangan cascade delete, agar data penjualan aman meski produk dihapus nanti
        $table->string('product_name'); // Simpan nama produk saat transaksi (jaga-jaga kalau nama menu berubah)
        $table->integer('quantity');
        $table->decimal('unit_price', 10, 2); // Harga per item saat transaksi
        $table->decimal('sub_total', 10, 2); // quantity * unit_price
        $table->json('note')->nullable(); // Fitur Penting! Simpan "Less Sugar", "Extra Ice" dalam format JSON
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
