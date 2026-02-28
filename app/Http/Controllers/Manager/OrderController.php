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

        $status = (string) $request->get('status', 'all');
        $shift  = (string) $request->get('shift', 'all');
        $search = (string) $request->get('search', '');

        $q = Order::query()->latest();

        if (!empty($manager->shift)) {
            $q->where(function ($qq) use ($manager) {
                $qq->whereNull('shift')->orWhere('shift', $manager->shift);
            });
        }

        if ($status !== 'all') {
            $q->where('status', $status);
        }

        if ($shift !== 'all') {
            $q->where('shift', $shift);
        }

        if ($search !== '') {
            $s = trim($search);

            $q->where(function ($qq) use ($s) {

                // 订单字段
                $qq->where('pickup', 'like', "%{$s}%")
                    ->orWhere('dropoff', 'like', "%{$s}%");

                // 如果是数字，搜订单ID
                if (ctype_digit($s)) {
                    $qq->orWhere('id', (int) $s);
                }

                // 搜司机
                $qq->orWhereHas('driver', function ($d) use ($s) {
                    $d->where('name', 'like', "%{$s}%")
                        ->orWhere('full_name', 'like', "%{$s}%")
                        ->orWhere('phone', 'like', "%{$s}%");
                });

                // 搜顾客
                $qq->orWhereHas('user', function ($u) use ($s) {
                    $u->where('name', 'like', "%{$s}%")
                        ->orWhere('full_name', 'like', "%{$s}%")
                        ->orWhere('phone', 'like', "%{$s}%");
                });
            });
        }

        $orders = $q->paginate(12)->withQueryString();

        return view('manager.orders.index', compact('orders', 'status', 'shift', 'search'));
    }

    public function show(Request $request, Order $order)
    {
        $manager = $request->user();

        $driversQuery = User::query()
            ->where('role', 'driver')
            ->where('driver_status', 'approved')
            // ✅ 只显示在线 + 还活着（5分钟内有心跳）
            ->where('is_online', 1)
            ->orderBy('name');

        // ✅ 默认只显示同 shift driver；点击 All Drivers 才显示全部
        if (!empty($manager->shift) && $request->get('drivers') !== 'all') {
            $driversQuery->where('shift', $manager->shift);
        }

        // ✅ （可选）如果你要允许查看离线司机：?drivers=offline
        if ($request->get('drivers') === 'offline') {
            $driversQuery = User::query()
                ->where('role', 'driver')
                ->where('driver_status', 'approved')
                ->where(function ($q) {
                    $q->where('is_online', 0)
                        ->orWhereNull('last_active_at')
                        ->orWhere('last_active_at', '<', now()->subMinutes(5));
                })
                ->orderBy('name');

            if (!empty($manager->shift) && $request->get('drivers') !== 'all') {
                $driversQuery->where('shift', $manager->shift);
            }
        }

        $drivers = $driversQuery->get(['id', 'name', 'shift']);

        return view('manager.orders.show', compact('order', 'drivers'));
    }

    public function assign(Request $request, Order $order)
    {
        $manager = $request->user();

        $data = $request->validate([
            'driver_id'   => ['required', 'integer', 'exists:users,id'],
            'payment_type' => ['required', Rule::in(['cash', 'credit', 'transfer'])],
            'amount'      => ['required', 'numeric', 'min:0'],
        ]);

        // ✅ 确认选的是 driver
        $driver = User::query()
            ->where('id', $data['driver_id'])
            ->where('role', 'driver')
            ->firstOrFail();

        if (!$driver->is_online) {
            return back()
                ->withErrors(['driver_id' => 'Driver is offline. Please select an online driver.'])
                ->withInput();
        }

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
            'amount'       => $data['amount'],
            'status'       => 'assigned',
        ]);

        return redirect()
            ->route('manager.orders.show', $order)
            ->with('status', 'Order assigned successfully.');
    }
}
