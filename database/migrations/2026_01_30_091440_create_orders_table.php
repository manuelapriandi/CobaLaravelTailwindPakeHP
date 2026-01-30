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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name')->nullable(); // Nama pelanggan (opsional)
        $table->string('transaction_code')->unique(); // Kode unik (misal: TRX-001)
        $table->enum('payment_method', ['cash', 'qris', 'transfer'])->default('cash');
        $table->enum('status', ['pending', 'paid', 'cancel'])->default('pending');
        $table->decimal('total_price', 12, 2);
        $table->decimal('paid_amount', 12, 2)->nullable(); // Uang yang dibayar (untuk kembalian)
        $table->decimal('change_amount', 12, 2)->nullable(); // Uang kembalian
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
