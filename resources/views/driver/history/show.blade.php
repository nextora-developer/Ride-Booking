@extends('layouts.driver-app')

@section('title', '订单详情')

@section('header')
    <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 bg-[#fbfbfc]/90 backdrop-blur-md border-b border-slate-50">
        {{-- Back Button --}}
        <div class="absolute left-0 top-1/2 -translate-y-1/2">
            <a href="{{ route('driver.history.index') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>

        {{-- Center Title --}}
        <div class="text-center">
            <h1 class="text-lg font-black text-slate-800 leading-none">订单详情</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Order Details</p>
        </div>
    </div>
@endsection

@section('content')
    @php
        // ===== 状态中文 + badge =====
        $statusZh = match ($order->status) {
            'completed' => '已完成',
            'cancelled' => '已取消',
            'assigned' => '已派单',
            'on_the_way' => '前往中',
            'arrived' => '已到达',
            'in_trip' => '行程中',
            default => $order->status,
        };

        $badge = match ($order->status) {
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
            'in_trip' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };

        // ===== 顾客信息 =====
        $customerName = $order->customer_name ?? (optional($order->user)->name ?? '顾客');
        $rawPhone = $order->customer_phone ?? (optional($order->user)->phone ?? null);

        $phoneDigits = $rawPhone ? preg_replace('/\D+/', '', $rawPhone) : null;
        if ($phoneDigits && str_starts_with($phoneDigits, '0')) {
            $phoneDigits = '60' . ltrim($phoneDigits, '0');
        }

        // ===== 金额 / pax / 支付方式中文 =====
        $amount = (float) ($order->amount ?? ($order->total ?? 0));
        $pax = (int) ($order->pax ?? 1);

        $payRaw = strtolower((string) ($order->payment_type ?? ''));
        $payZh = match ($payRaw) {
            'cash', '现金' => '现金',
            'transfer', '转账', 'bank' => '转账',
            'credit', '挂单', 'wallet' => '挂单',
            default => strtoupper($order->payment_type ?? '-'),
        };

        $scheduleZh = ($order->schedule_type ?? '') === 'scheduled' ? '预约单' : '即时单';

        // ===== 收款提示 =====
        $collectHint = null;
        if (in_array($payRaw, ['cash', '现金'])) {
            $collectHint = '⚠️ 需要收现金：RM ' . number_format($amount, 2);
        } elseif (in_array($payRaw, ['credit', '挂单', 'wallet'])) {
            $collectHint = '✅ 系统结算：无需向顾客收款';
        } elseif (in_array($payRaw, ['transfer', '转账', 'bank'])) {
            $collectHint = '⚠️ 转账单：请确认顾客已转账';
        }

        // ===== Maps =====
        $pickup = $order->pickup ?? '';
        $dropoff = $order->dropoff ?? '';
        $navPickupUrl = $pickup ? 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($pickup) : '#';
        $navDropoffUrl = $dropoff ? 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($dropoff) : '#';
    @endphp

    <div class="space-y-5 pb-24 mt-4 px-1">

    {{-- 1. 核心状态与金额 --}}
    <div class="bg-white rounded-[2.5rem] p-8 
                border border-slate-200
                shadow-[0_20px_50px_rgba(15,23,42,0.10)]
                relative overflow-hidden text-center">

        {{-- 装饰背景 --}}
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-slate-100 rounded-full"></div>

        <div class="relative z-10">
            <span
                class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black border {{ $badge }} uppercase tracking-[0.15em]">
                {{ $statusZh }}
            </span>

            <div class="mt-5 text-5xl font-black text-slate-900 tracking-tight">
                <span class="text-lg font-bold mr-1">RM</span>{{ number_format($amount, 2) }}
            </div>

            <div class="mt-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                订单 #{{ $order->id }} • {{ $order->created_at?->format('d M, h:i A') }}
            </div>
        </div>

        {{-- Info Pills --}}
        <div class="mt-8 grid grid-cols-3 gap-3">
            <div class="py-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest">乘客</div>
                <div class="text-base font-black text-slate-900 mt-1">{{ $pax }} 人</div>
            </div>

            <div class="py-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest">付款</div>
                <div class="text-base font-black text-slate-900 mt-1">{{ $payZh }}</div>
            </div>

            <div class="py-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest">类型</div>
                <div class="text-sm font-black text-slate-900 mt-1">{{ $scheduleZh }}</div>
            </div>
        </div>

        {{-- 收款提示 --}}
        @if ($collectHint)
            <div class="mt-8 -mx-8 -mb-8 py-4 bg-slate-900 text-white text-xs font-black tracking-widest uppercase">
                {{ $collectHint }}
            </div>
        @endif
    </div>

    {{-- 2. 顾客资料 --}}
    <div class="bg-white rounded-[2rem] p-6 
                border border-slate-200 
                shadow-[0_10px_30px_rgba(15,23,42,0.06)]
                flex items-center justify-between gap-4">

        <div class="flex items-center gap-4 min-w-0">
            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-xl shrink-0">
                👤
            </div>

            <div class="min-w-0">
                <div class="text-base font-black text-slate-900 truncate">{{ $customerName }}</div>
                <div class="text-[12px] font-bold text-slate-500 mt-1 truncate">
                    {{ $rawPhone ?? '无电话' }}
                </div>
            </div>
        </div>

        <div class="flex gap-2 shrink-0">
            <a href="{{ $phoneDigits ? 'tel:+' . $phoneDigits : '#' }}"
                class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-600 
                       flex items-center justify-center active:scale-90 
                       transition shadow-sm border border-emerald-200">
                📞
            </a>

            <a href="{{ $phoneDigits ? 'https://wa.me/' . $phoneDigits : '#' }}" target="_blank"
                class="w-11 h-11 rounded-2xl bg-indigo-100 text-indigo-600 
                       flex items-center justify-center active:scale-90 
                       transition shadow-sm border border-indigo-200">
                💬
            </a>
        </div>
    </div>

    {{-- 3. 路线 --}}
    <div class="bg-white rounded-[2rem] p-7 
                border border-slate-200 
                shadow-[0_10px_30px_rgba(15,23,42,0.06)]">

        <div class="relative">

            <div class="absolute left-[8px] top-6 bottom-6 w-[2px] border-l-2 border-dashed border-slate-200"></div>

            {{-- Pickup --}}
            <div class="relative flex items-start gap-4 mb-10">
                <div class="mt-1.5 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-emerald-100 shrink-0"></div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] font-black text-slate-500 uppercase tracking-widest">上车地点</div>
                        <a href="{{ $navPickupUrl }}" target="_blank"
                            class="text-[11px] font-black text-indigo-600 uppercase tracking-widest">
                            🧭 导航
                        </a>
                    </div>

                    <div class="text-base font-black text-slate-900 mt-2 leading-snug">
                        {{ $pickup ?: '未设置' }}
                    </div>
                </div>
            </div>

            {{-- Dropoff --}}
            <div class="relative flex items-start gap-4">
                <div class="mt-1.5 w-4 h-4 rounded-full bg-rose-500 ring-4 ring-rose-100 shrink-0"></div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] font-black text-slate-500 uppercase tracking-widest">下车地点</div>
                        <a href="{{ $navDropoffUrl }}" target="_blank"
                            class="text-[11px] font-black text-indigo-600 uppercase tracking-widest">
                            🧭 导航
                        </a>
                    </div>

                    <div class="text-base font-black text-slate-900 mt-2 leading-snug">
                        {{ $dropoff ?: '未设置' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. 备注 --}}
    @if ($order->note)
        <div class="bg-amber-100 rounded-[1.8rem] p-6 border border-amber-200">
            <div class="flex gap-4">
                <span class="text-lg shrink-0">📝</span>
                <div>
                    <div class="text-[11px] font-black text-amber-700 uppercase tracking-widest">
                        乘客备注
                    </div>
                    <div class="text-sm font-bold text-amber-900 mt-2 leading-relaxed">
                        {{ $order->note }}
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
