<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderDetail; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $allIngredients = Ingredient::all()->keyBy('name');

        $recipes = [
            'Americano' => ['Gallon Water', 'Espresso Coffee Beans', 'Plastic Cup Ice 16oz'],
            'Coffee Latte' => ['Espresso Coffee Beans', 'Fresh Milk', 'Plastic Cup Ice 16oz'],
            'Caramel Latte' => ['Espresso Coffee Beans', 'Fresh Milk', 'Caramel Syrup', 'Plastic Cup Ice 16oz'],
            'Chocolate' => ['Chocolate Powder', 'Gallon Water', 'Plastic Cup Ice 16oz'],
            'Matcha Latte' => ['Matcha Powder', 'Fresh Milk', 'Plastic Cup Ice 16oz'],
            'Croissant' => ['Croissant'],
            'Beef Toast Bread' => ['Beed Toast Bread'],
            'Cheesecake' => ['Cheesecake'],
            'Red Velvet Cake' => ['Red Velvet Cake']
        ];

        $products = Product::with(['category'])->get()->map(function($p) use ($recipes, $allIngredients) {
            $isAvailable = true;
            $stockText = 'Ready';

            if (isset($recipes[$p->name])) {
                $requiredIngredients = $recipes[$p->name];
                
                foreach ($requiredIngredients as $ingName) {
                    $ingredient = $allIngredients->get($ingName);
                    
                    if (!$ingredient || $ingredient->stock <= 0) {
                        $isAvailable = false;
                        $stockText = 'Habis: ' . $ingName;
                        break;
                    }
                }
            }

            return [
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'base_price' => (int) $p->base_price,
                'category_slug' => $p->category ? strtolower($p->category->slug) : 'other',
                'category_name' => $p->category ? $p->category->name : 'Other',
                'is_available' => $isAvailable,
                'stock_text' => $stockText,
            ];
        });

        $categories = Category::all();

        return view('kasir.kasir', compact('products', 'categories'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'payment_method' => 'required',
        ]);

        $status = ($request->payment_method === 'cash') ? 'completed' : 'pending';

        DB::beginTransaction();

        try {
            $subtotal = collect($request->cart)->sum(fn($item) => $item['base_price'] * $item['qty']);
            $tax = $subtotal * 0.1;
            $totalAmount = $subtotal + $tax;
            $status = ($request->payment_method === 'cash') ? 'completed' : 'pending';



            $order = Order::create([
                'order_number'  => 'ORD-' . date('YmdHis'),
                'user_id'       => auth()->id(),
                'shift_id'      => 1,
                'subtotal'      => $subtotal,
                'customer_name' => $request->customer_name ?? 'Guest',
                'order_type'    => $request->order_type ?? 'dine-in',
                'payment_method'=> $request->payment_method,
                'total_amount'  => $totalAmount,
                'status'        => 'completed',
            ]);

            foreach ($request->cart as $item) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price_at_transaction' => $item['base_price'],
                    'total_price' => $item['base_price'] * $item['qty'],
                ]);
            }

            DB::commit();

            if ($request->payment_method === 'midtrans') {
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
                \Midtrans\Config::$isProduction = false;

                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_number,
                        'gross_amount' => (int) $totalAmount,
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                return response()->json([
                    'snap_token' => $snapToken,
                    'order_id' => $order->id
                ], 200);

            } else {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id, // Tambahkan baris ini
                    'message' => 'Cash Transaction Saved Successfully!'
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction Failed: ' . $e->getMessage()], 500);
        }
    }
}