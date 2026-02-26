<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $shift = $request->query('shift'); // day / night (可选)

        $drivers = User::query()
            ->where('role', 'driver')
            ->when($shift, fn($qq) => $qq->where('shift', $shift))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($x) use ($q) {
                    $x->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.drivers.index', compact('drivers', 'q', 'shift'));
    }

    public function show(User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        // 最近 10 单
        $orders = Order::query()
            ->where('driver_id', $driver->id)
            ->latest()
            ->take(10)
            ->get();

        // 简单统计（可扩展）
        $stats = [
            'total'     => Order::where('driver_id', $driver->id)->count(),
            'completed' => Order::where('driver_id', $driver->id)->where('status', 'completed')->count(),
            'active'    => Order::where('driver_id', $driver->id)->whereIn('status', ['assigned','on_the_way','arrived','in_trip'])->count(),
        ];

        return view('admin.drivers.show', compact('driver', 'orders', 'stats'));
    }
}