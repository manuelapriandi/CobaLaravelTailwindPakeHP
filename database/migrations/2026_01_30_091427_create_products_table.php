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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        // Hubungkan ke kategori. Jika kategori dihapus, produk ikut terhapus (cascade)
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2); // Harga (mendukung desimal)
        $table->integer('stock')->default(0); // Stok harian
        $table->string('image')->nullable(); // Path gambar produk
        $table->boolean('is_available')->default(true); // Tombol on/off menu
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
