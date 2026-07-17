<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        // 1. STATISTIK HARI INI (Hanya hitung pendapatan dari pesanan 'completed')
        $todayTotal = Order::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 'completed')
            ->sum('total_amount');

        $todayCountSuccess = Order::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 'completed')
            ->count();

        $todayCountPending = Order::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 'pending')
            ->count();

        $todayCustomers = $todayCountSuccess + $todayCountPending;

        $days = 7;
        $daily = [];
        $weeklyTotal = 0;
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $start = $d->copy()->startOfDay();
            $end = $d->copy()->endOfDay();

            $sum = Order::query()
                ->whereBetween('created_at', [$start, $end])
                ->where('status', 'completed')
                ->sum('total_amount');

            $daily[] = [
                'label' => $d->format('D, d M'),
                'amount' => $sum,
            ];
            $weeklyTotal += $sum;
        }

        $dailyLabels = array_map(fn ($x) => $x['label'], $daily);
        $dailyAmounts = array_map(fn ($x) => (float) $x['amount'], $daily);

        $monthDays = 30;
        $monthly = [];
        for ($i = $monthDays - 1; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $start = $d->copy()->startOfDay();
            $end = $d->copy()->endOfDay();

            $monthly[] = [
                'label' => $d->format('d/m'),
                'amount' => Order::query()
                    ->whereBetween('created_at', [$start, $end])
                    ->where('status', 'completed')
                    ->sum('total_amount'),
            ];
        }
        
        $monthlyLabels = array_map(fn ($x) => $x['label'], $monthly);
        $monthlyAmounts = array_map(fn ($x) => (float) $x['amount'], $monthly);

        $monthTotal = Order::query()
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->where('status', 'completed')
            ->sum('total_amount');

        $recentOrders = Order::query()
            ->latest()
            ->take(7)
            ->get();

        $allTopItems = OrderDetail::query()
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->select('products.name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->get()
            ->map(fn($item) => [
                'name' => $item->name,
                'sold' => (int) $item->total_sold
            ])
            ->toArray();

        $topItems = array_slice($allTopItems, 0, 4);

        $totalSold = OrderDetail::query()
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->sum('order_details.quantity');

        $allTopCategories = OrderDetail::query()
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'completed')
            ->select('categories.name', DB::raw('SUM(order_details.quantity) as cat_total'))
            ->groupBy('categories.name')
            ->orderByDesc('cat_total')
            ->get()
            ->map(function($cat) use ($totalSold) {
                $colors = ['#244C38', '#3B7A5A', '#D99000', '#7A827E', '#4A524F', '#A3B8AD', '#C5DCD0'];
                static $i = 0;
                return [
                    'name' => $cat->name,
                    'sold' => (int) $cat->cat_total,
                    'percentage' => $totalSold > 0 ? round(($cat->cat_total / $totalSold) * 100) : 0,
                    'color' => $colors[$i++ % count($colors)]
                ];
            })
            ->toArray();

        $topCategories = array_slice($allTopCategories, 0, 4);

        return view('dashboard.dashboard', [
            'todayTotal' => $todayTotal,
            'weeklyTotal' => $weeklyTotal,
            'todayCustomers' => $todayCustomers,
            'todayCountSuccess' => $todayCountSuccess,
            'todayCountPending' => $todayCountPending,
            'dailyLabels' => $dailyLabels,
            'dailyAmounts' => $dailyAmounts,
            'monthlyLabels' => $monthlyLabels,
            'monthlyAmounts' => $monthlyAmounts,
            'monthTotal' => $monthTotal,
            'recentOrders' => $recentOrders,
            'topItems' => $topItems,
            'allTopItems' => $allTopItems,
            'topCategories' => $topCategories,
            'allTopCategories' => $allTopCategories,
        ]);
    }
}