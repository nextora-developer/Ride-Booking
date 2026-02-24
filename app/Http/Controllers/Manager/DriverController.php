<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;

class DriverController extends Controller
{
    public function index()
    {
        $managerShift = auth()->user()->shift; // 🌞 day / 🌙 night

        $drivers = User::where('role', 'driver')
            ->where('shift', $managerShift) // ✅ 只看同班
            ->orderBy('name')
            ->get();

        foreach ($drivers as $driver) {

            $currentOrder = Order::where('driver_id', $driver->id)
                ->whereIn('status', ['assigned', 'on_the_way', 'arrived'])
                ->latest()
                ->first();

            $driver->current_order = $currentOrder;
        }

        $total = $drivers->count();
        $onJob = $drivers->whereNotNull('current_order')->count();
        $available = $total - $onJob;

        return view('manager.drivers.index', compact(
            'drivers',
            'total',
            'onJob',
            'available'
        ));
    }
}
