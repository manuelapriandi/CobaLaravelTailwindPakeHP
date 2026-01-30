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
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama Kategori (misal: Coffee)
        $table->string('slug')->unique(); // Untuk URL friendly
        $table->string('icon')->nullable(); // Ikon kategori (opsional)
        $table->timestamps();
    });
}
     
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
