<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

        // Ringkasan hari ini
        $todayTotal = Order::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('payment_status', 'success')
            ->sum('total_amount');

        $todayCountSuccess = Order::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('payment_status', 'success')
            ->count();

        $todayCountPending = Order::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('payment_status', 'pending')
            ->count();

        // Chart pendapatan harian 7 hari terakhir (success)
        $days = 7;
        $daily = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $start = $d->copy()->startOfDay();
            $end = $d->copy()->endOfDay();

            $daily[] = [
                'label' => $d->format('d M'),
                'amount' => Order::query()
                    ->whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'success')
                    ->sum('total_amount'),
            ];
        }

        $dailyLabels = array_map(fn ($x) => $x['label'], $daily);
        $dailyAmounts = array_map(fn ($x) => (float) $x['amount'], $daily);

        // Total pendapatan bulan ini (success)
        $monthTotal = Order::query()
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->where('payment_status', 'success')
            ->sum('total_amount');

        return view('dashboard.dashboard', [
            'todayTotal' => $todayTotal,
            'todayCountSuccess' => $todayCountSuccess,
            'todayCountPending' => $todayCountPending,
            'dailyLabels' => $dailyLabels,
            'dailyAmounts' => $dailyAmounts,
            'monthTotal' => $monthTotal,
        ]);
    }
}

