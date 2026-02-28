<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', '');

        $orders = Order::query()
            ->where('driver_id', auth()->id())

            // 🔥 状态筛选逻辑
            ->when($status === 'ongoing', function ($query) {
                $query->whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip']);
            })

            ->when($status === 'completed', function ($query) {
                $query->where('status', 'completed');
            })

            ->when($status === 'cancelled', function ($query) {
                $query->where('status', 'cancelled');
            })

            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('pickup', 'like', "%{$q}%")
                        ->orWhere('dropoff', 'like', "%{$q}%")
                        ->orWhere('id', $q);
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = Order::where('driver_id', auth()->id())
            ->selectRaw("SUM(status='completed') as completed")
            ->selectRaw("SUM(status='cancelled') as cancelled")
            ->selectRaw("
            SUM(status IN ('assigned','on_the_way','arrived','in_trip')) as ongoing
        ")
            ->selectRaw("COUNT(*) as total")
            ->first();

        return view('driver.history.index', compact('orders', 'q', 'status', 'counts'));
    }

    public function show(Order $order)
    {
        abort_unless((int) $order->driver_id === (int) auth()->id(), 403);

        return view('driver.history.show', compact('order'));
    }
}
