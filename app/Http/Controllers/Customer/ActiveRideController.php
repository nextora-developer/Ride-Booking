<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ActiveRideController extends Controller
{
    public function show(Request $request)
    {
        $userId = $request->user()->id;

        $activeBooking = Order::where('user_id', $userId)
            ->whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip'])
            ->latest()
            ->with('driver')
            ->first();

        return view('customer.partials.active-ride', compact('activeBooking'));
    }
}