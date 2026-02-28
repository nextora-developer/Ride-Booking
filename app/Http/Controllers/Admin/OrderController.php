<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $q = $request->string('q')->toString();
        $payment_type = $request->string('payment_type')->toString();
        $shift = $request->string('shift')->toString();

        $ordersQuery = Order::with(['customer', 'driver', 'manager'])->latest();

        if (!empty($status)) {
            $ordersQuery->where('status', $status);
        }

        if (!empty($payment_type)) {
            $ordersQuery->where('payment_type', $payment_type);
        }

        if (!empty($shift)) {
            $ordersQuery->where('shift', $shift);
        }

        if (!empty($q)) {
            $ordersQuery->where(function ($qq) use ($q) {
                $qq->where('id', $q)
                    ->orWhere('pickup', 'like', "%{$q}%")
                    ->orWhere('dropoff', 'like', "%{$q}%")
                    ->orWhereHas('customer', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%")
                            ->orWhere('phone', 'like', "%{$q}%");
                    });
            });
        }

        $orders = $ordersQuery->paginate(10)->withQueryString();

        $drivers = User::where('role', 'driver')->orderBy('name')->get();

        return view('admin.orders.index', compact(
            'orders',
            'drivers',
            'status',
            'q',
            'payment_type',
            'shift'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'driver', 'manager']);

        $drivers = User::where('role', 'driver')->orderBy('name')->get();

        return view('admin.orders.show', compact('order', 'drivers'));
    }

    public function assign(Request $request, Order $order)
    {
        // ✅ 司机已经开始跑/完成/取消：锁定不能换
        $locked = ['on_the_way', 'arrived', 'in_trip', 'completed', 'cancelled'];

        if (in_array(strtolower((string) $order->status), $locked, true)) {
            return back()->withErrors(['status' => 'This order cannot be assigned at the current status.']);
        }

        $data = $request->validate([
            'driver_id'     => ['required', 'exists:users,id'],
            'payment_type'  => ['required', 'in:cash,credit,transfer'],
            'amount'        => ['required', 'numeric', 'min:0'],
        ]);

        $order->driver_id     = (int) $data['driver_id'];
        $order->payment_type  = $data['payment_type'];
        $order->amount        = (float) $data['amount']; // ✅ 存金额（RM）
        $order->status        = 'assigned';
        $order->assigned_at   = now();

        // ✅ payment_status 默认逻辑
        if ($data['payment_type'] === 'transfer') {
            $order->payment_status = $order->payment_status ?: 'pending'; // 等确认转账
        } elseif ($data['payment_type'] === 'cash') {
            $order->payment_status = $order->payment_status ?: 'unpaid'; // 司机收
        } else { // credit
            $order->payment_status = $order->payment_status ?: 'open';   // 挂账中（推荐）
        }

        $order->save();

        return back()->with('status', 'Driver assigned successfully.');
    }
}
