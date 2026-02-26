<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(5);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        // ✅ security: 只能看自己的订单
        abort_unless((int) $order->user_id === (int) $request->user()->id, 403);

        return view('customer.orders.show', compact('order'));
    }
}
