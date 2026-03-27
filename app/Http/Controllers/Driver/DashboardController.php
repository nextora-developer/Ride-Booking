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
        if ((int) $order->driver_id !== (int) auth()->id()) {
            abort(403);
        }

        $dropoffs = is_array($order->dropoffs ?? null)
            ? array_values(array_filter($order->dropoffs))
            : [];

        // 兼容旧系统：如果没有 dropoffs 数组，就用单个 dropoff
        if (empty($dropoffs) && !empty($order->dropoff)) {
            $dropoffs = [$order->dropoff];
        }

        $dropoffCount = count($dropoffs);
        $completedDropoffCount = (int) ($order->completed_dropoff_count ?? 0);

        /*
    |--------------------------------------------------------------------------
    | 多下车点推进
    |--------------------------------------------------------------------------
    | 当司机在 in_trip 阶段点击“到达第 X 个下车点 / 到达最终目的地”
    | 前端传：
    | action=complete_dropoff
    */
        if ($request->input('action') === 'complete_dropoff') {
            if ($order->status !== 'in_trip') {
                return back()->withErrors('状态更新失败：当前状态不能更新下车点。');
            }

            if ($dropoffCount <= 0) {
                return back()->withErrors('状态更新失败：当前订单没有下车点。');
            }

            if ($completedDropoffCount >= $dropoffCount) {
                return back()->withErrors('状态更新失败：所有下车点都已完成。');
            }

            $order->completed_dropoff_count = $completedDropoffCount + 1;

            // 所有下车点完成后，自动完成订单
            if ($order->completed_dropoff_count >= $dropoffCount) {
                $order->status = 'completed';
            }

            $order->save();

            return back()->with(
                'status',
                $order->status === 'completed'
                    ? '所有下车点已完成，行程已结束。'
                    : '已记录到达下一个下车点。'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | 普通状态推进
    |--------------------------------------------------------------------------
    */
        $request->validate([
            'status' => 'required|in:on_the_way,arrived,in_trip,completed',
        ]);

        $allowedTransitions = [
            'assigned' => 'on_the_way',
            'on_the_way' => 'arrived',
            'arrived' => 'in_trip',
            'in_trip' => 'completed',
        ];

        if (
            !isset($allowedTransitions[$order->status]) ||
            $allowedTransitions[$order->status] !== $request->status
        ) {
            return back()->withErrors('状态更新失败：流程不正确。');
        }

        // 如果有多个 dropoff，不允许 in_trip 直接 completed
        if (
            $order->status === 'in_trip' &&
            $request->status === 'completed' &&
            $dropoffCount > 1
        ) {
            return back()->withErrors('状态更新失败：请先完成所有下车点。');
        }

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('status', '行程状态已更新。');
    }
}
