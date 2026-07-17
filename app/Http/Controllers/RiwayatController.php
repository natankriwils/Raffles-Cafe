<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('payment_status', $request->input('status'));
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('transaksi.riwayat-transaksi', compact('orders'));
    }

    public function showStruk($id)
    {
        $order = Order::with('orderDetails.product')->findOrFail($id);
        
        return view('riwayat.struk', compact('order'));
    }
}