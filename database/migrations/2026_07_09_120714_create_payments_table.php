<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('payment_method', ['cash', 'qris', 'card', 'shopeepay', 'gopay']);
            $table->decimal('amount_paid', 14, 2);
            $table->decimal('change', 14, 2)->default(0);
            $table->string('reference_number')->nullable();
            $table->dateTime('payment_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};