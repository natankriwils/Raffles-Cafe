<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->dateTime('start_time'); 
            $table->dateTime('end_time')->nullable(); 
            
            $table->decimal('starting_cash', 14, 2);  
            $table->decimal('ending_cash', 14, 2)->nullable(); 
            $table->decimal('difference', 14, 2)->nullable(); 
            
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};