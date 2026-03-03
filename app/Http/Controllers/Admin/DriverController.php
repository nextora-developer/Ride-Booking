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
            ->when($q !== '', function ($query) use ($q) {

                $query->where(function ($x) use ($q) {

                    $x->where('name', 'like', "%{$q}%")
                        ->orWhere('full_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('car_plate', 'like', "%{$q}%")
                        ->orWhere('car_model', 'like', "%{$q}%");

                    // 数字：可能是 ID
                    if (is_numeric($q)) {
                        $x->orWhere('id', (int) $q);
                    }

                    // online/offline 关键字
                    $qLower = strtolower($q);
                    if ($qLower === 'online') {
                        $x->orWhere('is_online', true);
                    }
                    if ($qLower === 'offline') {
                        $x->orWhere('is_online', false);
                    }
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
            'active'    => Order::where('driver_id', $driver->id)->whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip'])->count(),
        ];

        return view('admin.drivers.show', compact('driver', 'orders', 'stats'));
    }

    public function edit(User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'full_name'  => ['nullable', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'car_plate'  => ['nullable', 'string', 'max:50'],
            'car_model'  => ['nullable', 'string', 'max:100'],
            'shift'      => ['required', 'in:day,night'],
            'is_online'  => ['nullable'],
        ]);

        $data['is_online'] = $request->boolean('is_online');

        $driver->update($data);

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with('success', 'Driver updated successfully.');
    }

    public function toggleOnline(User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        // ❗账号被 suspend 不允许 online
        if (!$driver->is_active) {
            return back()->with('error', 'Driver account is suspended.');
        }

        $driver->update([
            'is_online' => !$driver->is_online,
        ]);

        return back()->with('success', 'Online status updated.');
    }

    public function toggleAccount(User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        $newActive = !$driver->is_active;

        $driver->update([
            'is_active' => $newActive,
            // ❗一旦 suspend，强制下线
            'is_online' => $newActive ? $driver->is_online : false,
        ]);

        return back()->with('success', 'Account status updated.');
    }
}
