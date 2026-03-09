<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // ===== Date Range =====
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfDay();

        // Optional filters
        $shift = $request->input('shift'); // day|night|null
        $paymentType = $request->input('payment_type'); // cash|credit|transfer|null

        // ===== Base Query =====
        $base = Order::query()
            ->whereBetween('created_at', [$from, $to]);

        if (!empty($shift)) {
            $base->where('shift', $shift);
        }
        if (!empty($paymentType)) {
            $base->where('payment_type', $paymentType);
        }

        // ===== KPI Counts =====
        $totalOrders = (clone $base)->count();

        $completed = (clone $base)->where('status', 'completed')->count();
        $pending   = (clone $base)->where('status', 'pending')->count();
        $assigned  = (clone $base)->where('status', 'assigned')->count();
        $onTheWay  = (clone $base)->where('status', 'on_the_way')->count();
        $inTrip    = (clone $base)->where('status', 'in_trip')->count();
        $cancelled = (clone $base)->whereIn('status', ['cancelled', 'canceled'])->count();

        // ===== Revenue / AOV (amount) =====
        $revenue = (float) (clone $base)->sum('amount');
        $aov = $totalOrders > 0 ? $revenue / $totalOrders : 0;

        // ===== Status Breakdown =====
        $statusCounts = [
            'pending'    => $pending,
            'assigned'   => $assigned,
            'on_the_way' => $onTheWay,
            'in_trip'    => $inTrip,
            'completed'  => $completed,
            'cancelled'  => $cancelled,
        ];

        // ===== Payment Breakdown (count + sum amount) =====
        $paymentStats = (clone $base)
            ->select(
                'payment_type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(amount) as amount')
            )
            ->groupBy('payment_type')
            ->get()
            ->keyBy(fn($r) => $r->payment_type ?? 'unknown');

        // ===== Shift Breakdown (count + sum amount) =====
        $shiftStats = (clone $base)
            ->select(
                'shift',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(amount) as amount')
            )
            ->groupBy('shift')
            ->get()
            ->keyBy(fn($r) => $r->shift ?? 'unknown');

        // ===== Service Breakdown =====
        $serviceStats = (clone $base)
            ->select(
                'service_type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(amount) as amount')
            )
            ->groupBy('service_type')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ===== Daily Trend =====
        $daily = (clone $base)
            ->selectRaw("DATE(created_at) as d, COUNT(*) as orders, SUM(amount) as amount")
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $maxDailyOrders = (int) ($daily->max('orders') ?? 0);
        $maxDailyAmount = (float) ($daily->max('amount') ?? 0);

        // ===== Driver KPI (Completed) =====
        $driverStats = (clone $base)
            ->where('status', 'completed')
            ->select(
                'driver_id',
                DB::raw('COUNT(*) as trips'),
                DB::raw('SUM(amount) as amount')
            )
            ->groupBy('driver_id')
            ->with(['driver:id,name'])
            ->orderByDesc('trips')
            ->limit(30)
            ->get();

        return view('admin.reports.index', compact(
            'from',
            'to',
            'shift',
            'paymentType',
            'totalOrders',
            'completed',
            'pending',
            'assigned',
            'onTheWay',
            'inTrip',
            'cancelled',
            'revenue',
            'aov',
            'statusCounts',
            'paymentStats',
            'shiftStats',
            'serviceStats',
            'daily',
            'maxDailyOrders',
            'maxDailyAmount',
            'driverStats'
        ));
    }

    public function export(Request $request)
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfDay();

        $shift = $request->input('shift');
        $paymentType = $request->input('payment_type');

        $fileName = 'sales-report-' . $from->format('Ymd') . '-to-' . $to->format('Ymd') . '.xlsx';

        return Excel::download(
            new SalesReportExport(
                $from->toDateString(),
                $to->toDateString(),
                $shift,
                $paymentType
            ),
            $fileName
        );
    }
}
