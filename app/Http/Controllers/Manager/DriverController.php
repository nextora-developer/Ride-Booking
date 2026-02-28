<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $manager = $request->user();

        $shift = $manager->shift; // manager shift: day/night
        $q = User::query()->where('role', 'driver');

        // 默认只看同班司机（你要全部就把这段注释掉）
        if (!empty($shift)) {
            $q->where('shift', $shift);
        }

        // filters
        if ($request->filled('shift') && $request->shift !== 'all') {
            $q->where('shift', $request->shift);
        }

        if ($request->filled('online') && $request->online !== 'all') {
            $q->where('is_online', $request->online === '1');
        }

        if ($request->filled('driver_status') && $request->driver_status !== 'all') {
            $q->where('driver_status', $request->driver_status);
        }

        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('car_plate', 'like', "%{$s}%");
            });
        }

        // 取 active order（assigned/on_the_way/arrived/in_trip）
        $activeStatuses = ['assigned', 'on_the_way', 'arrived', 'in_trip'];

        // ✅ 1) paginate（这样 view 才能用 links()）
        $drivers = $q->orderBy('is_online', 'desc')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // ✅ 2) 批量拿每个 driver 的最新 active order（避免 N+1）
        $driverIds = $drivers->getCollection()->pluck('id')->all();

        if (!empty($driverIds)) {
            // 每个 driver 最新的一张 active order（用 MAX(id) 简单可靠）
            $latestOrderIds = Order::query()
                ->select(DB::raw('MAX(id) as id'))
                ->whereIn('driver_id', $driverIds)
                ->whereIn('status', $activeStatuses)
                ->groupBy('driver_id')
                ->pluck('id')
                ->all();

            $ordersByDriver = Order::query()
                ->whereIn('id', $latestOrderIds)
                ->get()
                ->keyBy('driver_id');

            // 塞回去（保持 paginator）
            $drivers->setCollection(
                $drivers->getCollection()->map(function ($d) use ($ordersByDriver) {
                    $d->current_order = $ordersByDriver->get($d->id); // 可能是 null
                    return $d;
                })
            );
        } else {
            $drivers->setCollection(
                $drivers->getCollection()->map(function ($d) {
                    $d->current_order = null;
                    return $d;
                })
            );
        }

        // ✅ 统计（当前页）
        $total = $drivers->count();
        $onJob = $drivers->getCollection()->filter(fn($d) => (bool) $d->current_order)->count();
        $available = $drivers->getCollection()->filter(fn($d) => !$d->current_order && (bool) $d->is_online)->count();

        return view('manager.drivers.index', compact('drivers', 'total', 'available', 'onJob'));
    }

    public function edit(Request $request, User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        // 默认：manager 只能改同 shift（你要允许跨 shift 就删掉）
        if (!empty($request->user()->shift) && !empty($driver->shift) && $driver->shift !== $request->user()->shift) {
            abort(403);
        }

        return view('manager.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        if (!empty($request->user()->shift) && !empty($driver->shift) && $driver->shift !== $request->user()->shift) {
            abort(403);
        }

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'full_name'     => ['required', 'string', 'max:255'],
            'shift'         => ['nullable', Rule::in(['day', 'night'])],
            'driver_status' => ['nullable', Rule::in(['pending', 'approved', 'rejected', 'inactive'])],

            'phone'         => ['nullable', 'string', 'max:30'],
            'email'         => ['nullable', 'email', 'max:255', 'unique:users,email,' . $driver->id],
            'ic_number'     => ['nullable', 'string', 'max:50'],
            'car_plate'     => ['nullable', 'string', 'max:30'],
            'car_model'     => ['nullable', 'string', 'max:80'],

            'bank_name'     => ['nullable', 'string', 'max:80'],
            'bank_account'  => ['nullable', 'string', 'max:80'],

            // 可选：经理可以直接切 online（你不要的话就删掉）
            'is_online'     => ['nullable', 'boolean'],
        ]);

        // checkbox 处理：没勾会没有值
        $data['is_online'] = (bool) ($request->input('is_online', $driver->is_online));

        $driver->update($data);

        return redirect()
            ->route('manager.drivers.index')
            ->with('status', 'Driver info updated ✅');
    }
}
