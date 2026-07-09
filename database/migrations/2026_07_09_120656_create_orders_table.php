<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Tetap pakai order_number sesuai strukturmu
            
            // Relasi ke operasional
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('shift_id')->constrained('shifts'); 
            
            // Detail pesanan
            $table->string('customer_name')->nullable(); 
            $table->enum('order_type', ['dine-in', 'take-away', 'delivery'])->default('dine-in');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            
            // Finansial
            $table->decimal('subtotal', 14, 2); 
            $table->decimal('discount', 14, 2)->default(0); 
            $table->decimal('tax', 14, 2)->default(0); 
            $table->decimal('total_amount', 14, 2); // Tetap pakai total_amount sesuai strukturmu
            
            // Tambahan Kolom untuk Sistem Kasir & Midtrans
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('snap_token')->nullable();
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('change', 14, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};