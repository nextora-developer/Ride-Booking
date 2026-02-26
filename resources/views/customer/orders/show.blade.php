@extends('layouts.customer-app')

@section('title', '订单 #' . $order->id)

@section('header')
    <div class="relative px-2">
        {{-- Mobile Navigation --}}
        <div class="md:hidden flex items-center justify-between h-7">
            <a href="{{ route('customer.orders.index') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-100 text-slate-900 shadow-sm active:scale-90 transition-transform">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div class="text-center">
                <h1 class="text-base font-black text-slate-900">订单详情</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">编号：#{{ $order->id }}</p>
            </div>
            <div class="w-11"></div> {{-- Spacer for balance --}}
        </div>

        {{-- Desktop Header --}}
        <div class="hidden md:flex items-center justify-between pb-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">订单详情</h1>
                <p class="text-slate-500 font-medium">订单编号 #{{ $order->id }}</p>
            </div>
            <a href="{{ route('customer.orders.index') }}"
                class="px-6 py-2.5 rounded-2xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                返回列表
            </a>
        </div>
    </div>
@endsection

@section('content')
    @php
        $statusMap = [
            'pending' => ['label' => '已下单', 'color' => 'bg-slate-100 text-slate-600', 'step' => 1],
            'assigned' => ['label' => '已派单', 'color' => 'bg-blue-100 text-blue-700', 'step' => 2],
            'on_the_way' => ['label' => '司机前往中', 'color' => 'bg-amber-100 text-amber-700', 'step' => 3],
            'arrived' => ['label' => '司机已到达', 'color' => 'bg-orange-100 text-orange-700', 'step' => 4],
            'in_trip' => ['label' => '行程进行中', 'color' => 'bg-purple-100 text-purple-700', 'step' => 5],
            'completed' => ['label' => '行程结束', 'color' => 'bg-emerald-100 text-emerald-700', 'step' => 6],
            'cancelled' => ['label' => '已取消', 'color' => 'bg-rose-100 text-rose-700', 'step' => 0],
        ];
        $current = $statusMap[$order->status] ?? $statusMap['pending'];

        $paymentText = fn($v) => match (strtolower((string) $v)) {
            'cash' => '现金',
            'credit' => '挂单',
            'transfer' => '转账',
            default => $v ?: '现金',
        };

        $shiftText = fn($v) => match (strtolower((string) $v)) {
            'day' => '早班',
            'night' => '晚班',
            default => $v ?: '早班',
        };
    @endphp

    <div class="pb-10 space-y-6">

        {{-- 1. Status Stepper Card --}}
        <div class="bg-white rounded-[2.5rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-50">
            <div class="flex items-center justify-between mb-8">
                <span
                    class="px-4 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-wider {{ $current['color'] }}">
                    {{ $current['label'] }}
                </span>

                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">
                        下单时间
                    </p>
                    <p class="text-xs font-black text-slate-900">
                        {{ $order->created_at->format('d M, H:i') }}
                    </p>
                </div>
            </div>

            {{-- Visual Stepper --}}
            @php
                $totalSteps = 6; // pending, assigned, on_the_way, arrived, in_trip, completed
                $progress = $current['step'] > 0 ? (($current['step'] - 1) / ($totalSteps - 1)) * 100 : 0;
            @endphp

            <div class="relative flex items-center justify-between px-2">
                <div class="absolute left-0 right-0 h-1 bg-slate-100 top-1/2 -translate-y-1/2"></div>

                <div class="absolute left-0 h-1 bg-slate-900 top-1/2 -translate-y-1/2 transition-all duration-700"
                    style="width: {{ $progress }}%"></div>

                @for ($i = 1; $i <= $totalSteps; $i++)
                    <div
                        class="relative h-4 w-4 rounded-full border-4 border-white shadow-sm transition-all duration-500
                {{ $current['step'] >= $i ? 'bg-slate-900 scale-125' : 'bg-slate-200' }}">
                    </div>
                @endfor
            </div>

            <div class="flex justify-between mt-4">
                <span class="text-[9px] font-bold text-slate-400 uppercase">已下单</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase">已派单</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase">前往中</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase">已到达</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase">行程中</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase text-right">完成</span>
            </div>
        </div>

        {{-- 2. Route Visualization Card --}}
        <div
            class="bg-white rounded-[2.5rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-50 overflow-hidden relative">
            <div class="absolute top-0 right-0 p-6 opacity-5">
                <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z" />
                </svg>
            </div>

            <div class="relative pl-10 space-y-10">
                {{-- Connector --}}
                <div class="absolute left-[13px] top-2 bottom-2 w-[2px] border-l-2 border-dashed border-slate-100"></div>

                {{-- Pickup --}}
                <div class="relative">
                    <div
                        class="absolute -left-[35px] top-1 h-6 w-6 rounded-full border-4 border-white bg-slate-200 flex items-center justify-center">
                        <div class="h-2 w-2 rounded-full bg-slate-500"></div>
                    </div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">上车地点</p>
                    <h3 class="text-base font-black text-slate-900 leading-snug">{{ $order->pickup }}</h3>
                </div>

                {{-- Dropoff --}}
                <div class="relative">
                    <div
                        class="absolute -left-[35px] top-1 h-6 w-6 rounded-full border-4 border-white bg-slate-900 flex items-center justify-center shadow-lg shadow-slate-200">
                        <div class="h-2 w-2 rounded-full bg-white"></div>
                    </div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">目的地</p>
                    <h3 class="text-base font-black text-slate-900 leading-snug">{{ $order->dropoff }}</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- 3. Driver Info Card --}}
            <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-50">
                <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">服务司机</h4>
                @if ($order->driver)
                    <div class="flex items-center gap-4">
                        <div
                            class="h-14 w-14 rounded-2xl bg-slate-900 flex items-center justify-center text-white font-black text-xl">
                            {{ substr($order->driver->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-slate-900">{{ $order->driver->name }}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">
                                {{ $order->driver->phone }}</p>
                        </div>
                        <a href="tel:{{ $order->driver->phone }}"
                            class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center active:scale-90 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="flex items-center gap-3 text-slate-400 py-2">
                        <div class="animate-spin h-4 w-4 border-2 border-slate-200 border-t-slate-400 rounded-full"></div>
                        <p class="text-xs font-bold">等待派单中...</p>
                    </div>
                @endif
            </div>

            {{-- 4. Trip Details --}}
            <div class="bg-slate-900 rounded-[2.5rem] p-6 text-white shadow-xl shadow-slate-200">
                <h4 class="text-[11px] font-black text-slate-300 uppercase tracking-widest mb-4">行程信息</h4>
                <div class="space-y-4">
                    @php
                        $serviceMap = [
                            'pickup_dropoff' => '接送',
                            'charter' => '包车',
                            'designated_driver' => '代驾',
                            'purchase' => '代购',
                            'big_car' => '大车',
                            'driver_only' => '司机',
                        ];
                    @endphp
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400 uppercase">服务</span>
                        <span class="text-sm font-black">
                            {{ $serviceMap[$order->service_type] ?? '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400 uppercase">PAX</span>
                        <span class="text-sm font-black">{{ $order->pax ?? 1 }} 人</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400 uppercase">付款方式</span>
                        <span class="text-sm font-black uppercase">{{ $paymentText($order->payment_type) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400 uppercase">班次</span>
                        <span class="text-sm font-black capitalize">{{ $shiftText($order->shift) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Notes Card --}}
        @if ($order->note)
            <div class="bg-amber-50 rounded-[2rem] p-6 border border-amber-100/50">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <span class="text-[11px] font-black text-amber-700 uppercase tracking-widest">特别备注</span>
                </div>
                <p class="text-sm font-semibold text-amber-900 leading-relaxed">“{{ $order->note }}”</p>
            </div>
        @endif

    </div>
@endsection
