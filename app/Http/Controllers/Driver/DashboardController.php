<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $driverId = auth()->id();

        $currentOrder = Order::where('driver_id', $driverId)
            ->whereIn('status', ['assigned', 'on_the_way', 'arrived'])
            ->latest()
            ->first();

        $todayOrders = Order::where('driver_id', $driverId)
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('driver.dashboard', compact(
            'currentOrder',
            'todayOrders'
        ));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->driver_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:on_the_way,arrived,in_trip,completed',
        ]);

        $allowedTransitions = [
            'assigned'    => 'on_the_way',
            'on_the_way'  => 'arrived',
            'arrived'     => 'in_trip',
            'in_trip'     => 'completed',
        ];

        if (
            !isset($allowedTransitions[$order->status]) ||
            $allowedTransitions[$order->status] !== $request->status
        ) {
            return back()->withErrors('Invalid status transition.');
        }

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('status', 'Trip updated successfully.');
    }
}
