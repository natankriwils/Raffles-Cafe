<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;       
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->get(); 

        return view('kasir.dashboard', compact('categories', 'products'));
    }

    public function checkout(Request $request)
    {
        \Midtrans\Config::$serverKey = 'SB-Mid-server-c3v6nUBhZGPQGv0DFIk_qg5W';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

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
        
        $orderNumber = 'RC-' . time() . '-' . rand(100, 999); 
        $userId = auth()->id() ?? 1; 
        $shiftId = 1;

        DB::beginTransaction();
        try {
            if ($request->payment_method === 'cash') {
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $userId,
                    'shift_id' => $shiftId,
                    'customer_name' => $request->customer_name ?? 'Pelanggan Umum',
                    'order_type' => $request->order_type,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total_amount' => $totalTagihan, 
                    'payment_method' => 'cash',
                    'payment_status' => 'success', 
                    'amount_paid' => $totalTagihan,
                    'change' => 0,
                ]);

                foreach ($cartItems as $item) {
                    $product = \App\Models\Product::find($item['id']);
                    if (! $product) {
                        continue;
                    }

                    $qty = (int) ($item['qty'] ?? 0);
                    $priceAtTransaction = $product->base_price;
                    $totalPrice = $priceAtTransaction * $qty;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'quantity' => $qty,
                        'price_at_transaction' => $priceAtTransaction,
                        'total_price' => $totalPrice,
                    ]);
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Transaksi Tunai Berhasil']);
            }

            if ($request->payment_method === 'midtrans') {
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $userId,
                    'shift_id' => $shiftId,
                    'customer_name' => $request->customer_name ?? 'Pelanggan Umum',
                    'order_type' => $request->order_type,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total_amount' => $totalTagihan,
                    'payment_method' => 'midtrans',
                    'payment_status' => 'pending',
                    'amount_paid' => 0,
                    'change' => 0,
                ]);

                foreach ($cartItems as $item) {
                    $qty = (int) ($item['qty'] ?? 0);

                    $priceAtTransaction = $item['price'] ?? null;
                    if ($priceAtTransaction === null) {
                        $product = \App\Models\Product::find($item['id']);
                        $priceAtTransaction = $product?->base_price;
                    }

                    if ($priceAtTransaction === null || $qty <= 0) {
                        continue;
                    }

                    $totalPrice = $priceAtTransaction * $qty;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'quantity' => $qty,
                        'price_at_transaction' => $priceAtTransaction,
                        'total_price' => $totalPrice,
                    ]);
                }

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderNumber,
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
                    'order_id' => $orderNumber
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