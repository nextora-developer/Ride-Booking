<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\CreditLog;
use Illuminate\Support\Facades\DB;


class OrderObserver
{
    public function updated(\App\Models\Order $order)
    {
        // after-save：用 wasChanged 才准
        if ($order->wasChanged('status') && $order->status === 'completed') {

            // 只处理挂单
            if ((string) $order->payment_type === 'credit') {

                DB::transaction(function () use ($order) {

                    $customer = $order->customer; // ✅ 你有 customer() 关系

                    if (!$customer) return;

                    $before = (float) ($customer->credit_balance ?? 0);
                    $amount = (float) ($order->amount ?? 0);

                    if ($amount <= 0) return;

                    $after = $before + $amount;

                    $customer->update(['credit_balance' => $after]);

                    CreditLog::create([
                        'customer_id' => $customer->id,
                        'manager_id'  => $order->manager_id, // 有 manager 就记录，没有就 null
                        'before'      => $before,
                        'change'      => $amount,
                        'after'       => $after,
                        'action'      => 'order_completed',
                        'note'        => 'Order #' . $order->id . ' completed (credit)',
                    ]);
                });
            }
        }
    }
}
