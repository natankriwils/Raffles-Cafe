<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            // Menghubungkan varian langsung ke produk spesifik
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name'); // Contoh: 'Regular', 'Large', 'Arabica Blend'
            $table->decimal('additional_price', 14, 2)->default(0); // Tambahan harga (misal: Large +Rp 5.000)
            $table->boolean('is_available')->default(true); // Stok varian ini ready atau habis
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};