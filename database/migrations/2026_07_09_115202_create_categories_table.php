<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Coffee, Non-Coffee, Pastry
            $table->string('slug')->unique(); // Untuk kebutuhan URL yang rapi (Contoh: 'non-coffee')
            $table->boolean('is_active')->default(true); // Memudahkan arsip kategori tanpa hapus data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};