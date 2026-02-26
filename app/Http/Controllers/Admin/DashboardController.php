<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $from = now()->subDays(30);

        // 订单统计
        $total30 = Order::where('created_at', '>=', $from)->count();

        $pending = Order::where('status', 'pending')->count(); // 你之前叫 unassigned，这里用 pending
        $active = Order::whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip'])->count();

        // 今日 revenue（按 completed 的订单 + paid / cash? 你先用最简单：completed 都算）
        // ⚠ 你 Order 没有 amount 字段，所以先做 count / 或你以后加 fare 才能 sum
        $todayCompleted = Order::whereDate('created_at', today())->where('status', 'completed')->count();

        // Driver stats
        $driversTotal = User::where('role', 'driver')->count();
        $driversDay   = User::where('role', 'driver')->where('shift', 'day')->count();
        $driversNight = User::where('role', 'driver')->where('shift', 'night')->count();

        // Latest bookings
        $latestOrders = Order::with(['customer', 'driver'])
            ->latest()
            ->take(10)
            ->get();

        // Payment mix (Today) - 按今天 created 的订单统计 payment_type
        $today = Order::whereDate('created_at', today());
        $todayTotal = (clone $today)->count();

        $cash = (clone $today)->where('payment_type', 'cash')->count();
        $credit = (clone $today)->where('payment_type', 'credit')->count();
        $transfer = (clone $today)->where('payment_type', 'transfer')->count();

        $pct = function ($n) use ($todayTotal) {
            return $todayTotal > 0 ? (int) round($n / $todayTotal * 100) : 0;
        };

        $mix = [
            ['name' => 'Cash',     'pct' => $pct($cash),     'count' => $cash,     'class' => 'bg-black'],
            ['name' => 'Credit',   'pct' => $pct($credit),   'count' => $credit,   'class' => 'bg-rose-500'],
            ['name' => 'Transfer', 'pct' => $pct($transfer), 'count' => $transfer, 'class' => 'bg-emerald-500'],
        ];

        // Alerts
        $pendingCredit = Order::where('payment_type', 'credit')
            ->whereIn('payment_status', ['open', 'unpaid', 'pending', null])
            ->count();

        return view('admin.dashboard', compact(
            'total30',
            'pending',
            'active',
            'todayCompleted',
            'driversTotal',
            'driversDay',
            'driversNight',
            'latestOrders',
            'mix',
            'pendingCredit'
        ));
    }
}
