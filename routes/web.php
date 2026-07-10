<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;

Route::get('/kasir', [KasirController::class, 'index']);
Route::post('/kasir/checkout', [KasirController::class, 'checkout']);
Route::post('/kasir/notification', [KasirController::class, 'notification']);
Route::get('/simulasi-lunas/{order_id}', function($order_id) {
    $order = \App\Models\Order::where('order_number', $order_id)->first();
    if ($order) {
        $order->update(['payment_status' => 'success', 'status' => 'completed']);
        return "Order ID $order_id berhasil diubah menjadi SUCCESS di database lokal!";
    }
    return "Order ID tidak ditemukan.";
});
Route::get('/tes-database', function() {
    return response()->json([
        'total_kategori' => \App\Models\Category::count(),
        'total_produk' => \App\Models\Product::count(),
        'semua_produk' => \App\Models\Product::all()
    ]);
});