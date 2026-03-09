<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        protected $from,
        protected $to,
        protected $shift = null,
        protected $paymentType = null,
    ) {}

    public function query(): Builder
    {
        return Order::query()
            ->with(['customer', 'driver'])
            ->where('status', 'completed') // sales report 只导出已完成
            ->whereDate('created_at', '>=', $this->from)
            ->whereDate('created_at', '<=', $this->to)
            ->when($this->shift, function ($q) {
                $q->where('shift', $this->shift);
            })
            ->when($this->paymentType, function ($q) {
                $q->where('payment_type', $this->paymentType);
            })
            ->latest();
    }

    public function headings(): array
    {
        return [
            '订单号',
            '日期时间',
            '顾客',
            '服务类型',
            '班次',
            '付款方式',
            '金额 (RM)',
            '上车点',
            '下车点',
            '司机',
            '状态',
        ];
    }

    public function map($order): array
    {
        $serviceLabel = match (strtolower((string) $order->service_type)) {
            'pickup_dropoff' => '接送',
            'charter' => '包车',
            'designated_driver' => '代驾',
            'purchase' => '代购',
            'big_car' => '大车',
            'driver_only' => '司机',
            default => $order->service_type ?: '—',
        };

        $paymentLabel = match (strtolower((string) $order->payment_type)) {
            'cash' => '现金',
            'credit' => '挂单',
            'transfer' => '转账',
            default => $order->payment_type ?: '—',
        };

        $shiftLabel = match (strtolower((string) $order->shift)) {
            'day' => '白班',
            'night' => '夜班',
            default => '—',
        };

        $statusLabel = match (strtolower((string) $order->status)) {
            'pending' => '待派单',
            'assigned' => '已派单',
            'on_the_way' => '前往中',
            'arrived' => '已到达',
            'in_trip' => '行程中',
            'completed' => '已完成',
            'cancelled' => '已取消',
            default => $order->status ?: '—',
        };

        $dropoffText = '—';

        if (!empty($order->dropoffs) && is_array($order->dropoffs)) {
            $dropoffText = implode(' | ', array_filter($order->dropoffs));
        } elseif (!empty($order->dropoff)) {
            $dropoffText = $order->dropoff;
        }

        return [
            'ORD-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
            optional($order->created_at)->format('Y-m-d H:i:s'),
            optional($order->customer)->name ?? '—',
            $serviceLabel,
            $shiftLabel,
            $paymentLabel,
            number_format((float) ($order->amount ?? 0), 2, '.', ''),
            $order->pickup ?? '—',
            $dropoffText,
            optional($order->driver)->name ?? '—',
            $statusLabel,
        ];
    }
}
