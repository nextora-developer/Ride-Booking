<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to   = $request->date('to') ?? now();

        $query = Order::whereBetween('created_at', [$from, $to]);

        $totalOrders = (clone $query)->count();
        $completed   = (clone $query)->where('status', 'completed')->count();
        $pending     = (clone $query)->where('status', 'pending')->count();

        // Payment breakdown
        $cash     = (clone $query)->where('payment_type', 'cash')->count();
        $credit   = (clone $query)->where('payment_type', 'credit')->count();
        $transfer = (clone $query)->where('payment_type', 'transfer')->count();

        // Driver performance (completed only)
        $driverStats = Order::selectRaw('driver_id, COUNT(*) as total')
            ->whereBetween('created_at', [$from, $to])
            ->where('status', 'completed')
            ->groupBy('driver_id')
            ->with('driver')
            ->orderByDesc('total')
            ->get();

        return view('admin.reports.index', compact(
            'from',
            'to',
            'totalOrders',
            'completed',
            'pending',
            'cash',
            'credit',
            'transfer',
            'driverStats'
        ));
    }
}
