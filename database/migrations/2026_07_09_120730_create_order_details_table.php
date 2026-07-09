<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('variant_id')->nullable()->constrained('variants'); // Nullable jika produk tidak punya varian ukuran
            
            $table->integer('quantity');
            $table->decimal('price_at_transaction', 14, 2); // Menyimpan harga saat itu (antisipasi jika besok harga menu naik)
            $table->decimal('total_price', 14, 2); // (Price * Quantity) + harga varian
            $table->text('notes')->nullable(); // Catatan item (Contoh: "Less ice, normal sugar")
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};