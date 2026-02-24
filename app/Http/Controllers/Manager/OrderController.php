<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $manager = $request->user();

        $q = Order::query()->latest();

        // ✅ 默认：只看自己 shift（想看全部就注释掉）
        if (!empty($manager->shift)) {
            $q->where(function ($qq) use ($manager) {
                $qq->whereNull('shift')->orWhere('shift', $manager->shift);
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $q->where('status', $request->status);
        }

        if ($request->filled('shift') && $request->shift !== 'all') {
            $q->where('shift', $request->shift);
        }

        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $q->where(function ($qq) use ($s) {
                $qq->where('pickup', 'like', "%{$s}%")
                    ->orWhere('dropoff', 'like', "%{$s}%")
                    ->orWhere('id', $s);
            });
        }

        $orders = $q->paginate(12)->withQueryString();

        return view('manager.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        $manager = $request->user();

        $driversQuery = User::query()
            ->where('role', 'driver')
            ->orderBy('name');

        // ✅ 默认只显示同 shift driver；点击 All Drivers 才显示全部
        if (!empty($manager->shift) && $request->get('drivers') !== 'all') {
            $driversQuery->where('shift', $manager->shift);
        }

        // ✅ 不取 phone（你 users 表没有 phone）
        $drivers = $driversQuery->get(['id', 'name', 'shift']);

        return view('manager.orders.show', compact('order', 'drivers'));
    }

    public function assign(Request $request, Order $order)
    {
        $manager = $request->user();

        $data = $request->validate([
            'driver_id' => ['required', 'integer', 'exists:users,id'],
            'payment_type' => ['required', Rule::in(['cash', 'credit', 'transfer'])],
        ]);

        // ✅ 确认选的是 driver
        $driver = User::query()
            ->where('id', $data['driver_id'])
            ->where('role', 'driver')
            ->firstOrFail();

        // ✅ 默认限制：manager 只能派同 shift 的 driver（要允许跨班次就删掉）
        if (!empty($manager->shift) && !empty($driver->shift) && $driver->shift !== $manager->shift) {
            return back()->withErrors(['driver_id' => 'Selected driver is not in your shift.'])->withInput();
        }

        // ✅ 只允许 pending 才能派（你要允许改派就改掉这段）
        if ($order->status !== 'pending') {
            return back()->withErrors(['driver_id' => 'Only pending orders can be assigned.'])->withInput();
        }

        $order->update([
            'driver_id'    => $driver->id,
            'manager_id'   => $manager->id,
            'assigned_at'  => Carbon::now(),
            'payment_type' => $data['payment_type'],
            'status'       => 'assigned',
        ]);

        return redirect()
            ->route('manager.orders.show', $order)
            ->with('status', 'Order assigned successfully.');
    }
}
