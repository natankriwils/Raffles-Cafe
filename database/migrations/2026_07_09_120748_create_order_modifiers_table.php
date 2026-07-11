<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_id')->constrained('order_details')->onDelete('cascade');
            $table->foreignId('modifier_id')->constrained('modifiers');
            $table->decimal('price_at_transaction', 14, 2); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_modifiers');
    }
};