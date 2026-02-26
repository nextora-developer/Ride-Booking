<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = $request->user()->id;

        $totalTrips = Order::where('user_id', $userId)->count();

        $inProgress = Order::where('user_id', $userId)
            ->whereIn('status', [
                'pending',
                'assigned',
                'on_the_way',
                'arrived',
                'in_trip'
            ])
            ->count();

        $completed = Order::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $recent = Order::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        // ✅ 当前进行中的订单（只取一个）
        $activeBooking = Order::where('user_id', $userId)
            ->whereIn('status', [
                'assigned',
                'on_the_way',
                'arrived',
                'in_trip'
            ])
            ->latest()
            ->with('driver') // 记得有 driver relationship
            ->first();

        return view('customer.home', compact(
            'totalTrips',
            'inProgress',
            'completed',
            'recent',
            'activeBooking'
        ));
    }
}
