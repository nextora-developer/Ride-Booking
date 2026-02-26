<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;


class DriverOnlineController extends Controller
{
    public function online(Request $request)
    {
        auth()->user()->update([
            'is_online' => true,
            'last_active_at' => now(),
        ]);

        return back()->with('status', '你已上线 ✅');
    }


    public function offline()
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->role === 'driver', 403);

        $hasActive = Order::where('driver_id', auth()->id())
            ->whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip'])
            ->exists();

        if ($hasActive) {
            return back()->withErrors(['offline' => '你还有进行中订单，不能下线。']);
        }

        auth()->user()->update(['is_online' => false]);

        return back()->with('status', '你已下线 ⛔');
    }
}
