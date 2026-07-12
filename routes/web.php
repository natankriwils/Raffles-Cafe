<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\MenuManagementController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/checkout', [KasirController::class, 'checkout'])->name('kasir.checkout');

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/riwayat-transaksi', [\App\Http\Controllers\RiwayatController::class, 'index'])->name('riwayat.index');
});

Route::delete('/riwayat-transaksi/{id}', [App\Http\Controllers\TransaksiController::class, 'destroy'])->name('transaksi.destroy');

Route::get('/', [\App\Http\Controllers\RedirectController::class, 'home'])->middleware('auth')->name('home');

Route::get('/simulasi-lunas/{order_id}', function($order_id) {
    $order = \App\Models\Order::where('order_number', $order_id)->first();
    if ($order) {
        $order->update(['payment_status' => 'success', 'status' => 'completed']);
        return "Order ID $order_id berhasil diubah menjadi SUCCESS di database lokal!";
    }
    return "Order ID tidak ditemukan.";
});

Route::middleware(['auth'])->group(function () {
    Route::resource('kelola-menu', MenuManagementController::class);
    Route::post('kelola-menu/category', [MenuManagementController::class, 'storeCategory'])->name('kelola-menu.storeCategory');
    Route::delete('kelola-menu/category/{id}', [MenuManagementController::class, 'destroyCategory'])->name('kelola-menu.destroyCategory');
});