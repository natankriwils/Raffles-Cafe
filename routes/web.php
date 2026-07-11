<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KasirController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/checkout', [KasirController::class, 'checkout'])->name('kasir.checkout');

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard.index');

    Route::get('/riwayat-transaksi', [\App\Http\Controllers\RiwayatController::class, 'index'])
        ->name('riwayat.index');
});

Route::get('/', [\App\Http\Controllers\RedirectController::class, 'home'])->middleware('auth')->name('home');


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