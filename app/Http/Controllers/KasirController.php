<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;       
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('variants')->get();
        
        return view('kasir.dashboard', compact('categories', 'products'));
    }

    public function checkout(Request $request)
    {
        // 1. Konfigurasi Awal Midtrans SDK
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        // 2. Hitung Total Matematis di Backend
        $subtotal = 0;
        $cartItems = $request->cart;
        
        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $subtotal += $product->base_price * $item['qty'];
            }
        }

        $tax = round($subtotal * 0.1); 
        $totalTagihan = $subtotal + $tax;
        $orderId = 'RC-' . time() . '-' . rand(100, 999); 

        // Ambil ID Kasir & Shift yang sedang login (Tembak manual id=1 jika belum membuat sistem auth login)
        $userId = auth()->id() ?? 1; 
        $shiftId = 1; // Sesuaikan dengan ID shift aktif di databasemu

        // 3. Gunakan Database Transaction
        DB::beginTransaction();
        try {
            // Skenario A: TUNAI / CASH
            if ($request->payment_method === 'cash') {
                $order = Order::create([
                    'order_number' => $orderId, // Menyesuaikan database
                    'user_id' => $userId,
                    'shift_id' => $shiftId,
                    'customer_name' => $request->customer_name ?? 'Pelanggan Umum',
                    'order_type' => $request->order_type,
                    'status' => 'completed', 
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total_amount' => $totalTagihan, // Menyesuaikan database
                    'payment_method' => 'cash',
                    'payment_status' => 'success', 
                    'amount_paid' => $request->amount_paid,
                    'change' => $request->amount_paid - $totalTagihan,
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                    ]);
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Transaksi Tunai Berhasil']);
            }

            // Skenario B: MIDTRANS
            if ($request->payment_method === 'midtrans') {
                $order = Order::create([
                    'order_number' => $orderId, // Menyesuaikan database
                    'user_id' => $userId,
                    'shift_id' => $shiftId,
                    'customer_name' => $request->customer_name ?? 'Pelanggan Umum',
                    'order_type' => $request->order_type,
                    'status' => 'pending',
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total_amount' => $totalTagihan, // Menyesuaikan database
                    'payment_method' => 'midtrans',
                    'payment_status' => 'pending', 
                    'amount_paid' => 0,
                    'change' => 0,
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                    ]);
                }

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId, 
                        'gross_amount' => (int) $totalTagihan,
                    ],
                    'customer_details' => [
                        'first_name' => $request->customer_name ?? 'Pelanggan',
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);

                DB::commit();
                
                return response()->json([
                    'snap_token' => $snapToken,
                    'order_id' => $orderId
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function notification(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            $notif = new \Midtrans\Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Menggunakan order_number sesuai struktur asli tabel orders
            $order = Order::where('order_number', $orderId)->first();

            if ($order) {
                if ($transaction == 'capture') {
                    if ($type == 'credit_card') {
                        if ($fraud == 'challenge') {
                            $order->update(['payment_status' => 'challenge']);
                        } else {
                            $order->update(['payment_status' => 'success', 'status' => 'completed']);
                        }
                    }
                } else if ($transaction == 'settlement') {
                    $order->update(['payment_status' => 'success', 'status' => 'completed']);
                } else if ($transaction == 'pending') {
                    $order->update(['payment_status' => 'pending']);
                } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                    $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                }
            }

            return response()->json(['success' => true, 'message' => 'Notification handled']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}