@extends('layouts.manager-app')

@section('title', '订单详情')

@section('header')
    {{-- Minimal Header Pattern --}}
    <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">

        {{-- Back Button --}}
        <div class="absolute left-4 top-1/2 -translate-y-1/2">
            <a href="{{ route('manager.orders.index') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>

        {{-- Center Title --}}
        <div class="text-center">
            <h1 class="text-lg font-black text-slate-800 leading-none">
                订单
            </h1>

            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">
                #{{ $order->id }}
            </p>
        </div>
        {{-- Status (Right Side) --}}
        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2">

            @php
                $statusMap = [
                    'pending' => ['color' => 'bg-amber-500', 'label' => '待处理'],
                    'assigned' => ['color' => 'bg-indigo-500', 'label' => '已派单'],
                    'on_the_way' => ['color' => 'bg-blue-500', 'label' => '司机前往中'],
                    'arrived' => ['color' => 'bg-purple-500', 'label' => '已到达'],
                    'in_trip' => ['color' => 'bg-sky-500', 'label' => '行程中'],
                    'completed' => ['color' => 'bg-emerald-500', 'label' => '已完成'],
                    'cancelled' => ['color' => 'bg-rose-500', 'label' => '已取消'],
                ];

                $status = $statusMap[$order->status] ?? [
                    'color' => 'bg-slate-400',
                    'label' => ucfirst(str_replace('_', ' ', $order->status)),
                ];
            @endphp

            <span class="h-2 w-2 rounded-full {{ $status['color'] }} animate-pulse"></span>

            <span class="text-[11px] font-black text-slate-600 uppercase tracking-tighter">
                {{ $status['label'] }}
            </span>

        </div>

    </div>
@endsection

@section('content')
    @php
        $when =
            $order->schedule_type === 'scheduled' && $order->scheduled_at ? $order->scheduled_at : $order->created_at;
        $payOptions = ['cash' => '现金', 'credit' => '挂单', 'transfer' => '转账'];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-4 pb-7">

        {{-- Left: Route & Info --}}
        <div class="lg:col-span-7 space-y-4">

            {{-- Customer Info --}}
            <div
                class="bg-white rounded-[2.5rem] p-6
                    shadow-[0_12px_30px_rgba(15,23,42,0.08)]
                    border border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 min-w-0">
                        <div
                            class="h-12 w-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-lg
                               shadow-[0_14px_30px_rgba(15,23,42,0.20)]">
                            {{ strtoupper(substr($order->user?->full_name ?? ($order->user?->name ?? 'U'), 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <div class="text-xs font-black text-slate-400 uppercase tracking-widest leading-none">
                                顾客信息
                            </div>

                            <div class="text-lg font-black text-slate-900 truncate mt-1">
                                {{ $order->user?->full_name ?? ($order->user?->name ?? '未知顾客') }}
                            </div>

                            <div class="text-sm font-bold text-slate-600 truncate mt-1">
                                {{ $order->user?->phone ?? '无电话号码' }}
                            </div>
                        </div>
                    </div>

                    @if ($order->user?->phone)
                        <a href="tel:{{ $order->user->phone }}"
                            class="h-10 w-10 rounded-full bg-slate-100 border border-slate-200
                               flex items-center justify-center text-slate-700 hover:bg-slate-200 transition"
                            aria-label="拨打电话">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Route Card --}}
            <div
                class="bg-white rounded-[2.5rem] p-8
                    shadow-[0_14px_34px_rgba(15,23,42,0.08)]
                    border border-slate-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-[0.04] pointer-events-none">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                    </svg>
                </div>

                <div class="absolute -left-12 -bottom-12 h-44 w-44 rounded-full bg-slate-100 opacity-70"></div>

                <div class="relative z-10">
                    <div class="flex items-start gap-5">
                        <div class="flex flex-col items-center gap-1 shrink-0 mt-1">
                            <div class="h-3 w-3 rounded-full border-2 border-slate-300 bg-white"></div>
                            <div class="h-16 w-0.5 border-l-2 border-slate-200 border-dashed"></div>
                            <div class="h-3 w-3 rounded-full bg-slate-900 shadow-[0_12px_26px_rgba(15,23,42,0.18)]"></div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="mb-6">
                                <label class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">上车地点</label>
                                <div class="text-xl font-black text-slate-900 mt-1 truncate">{{ $order->pickup }}</div>
                            </div>

                            <div>
                                <label class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">目的地</label>
                                <div class="text-xl font-black text-slate-900 mt-1 truncate">{{ $order->dropoff }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div
                    class="bg-white rounded-[2rem] p-6
                        shadow-[0_10px_24px_rgba(15,23,42,0.06)]
                        border border-slate-200">
                    <div class="text-xs font-black text-slate-500 uppercase tracking-widest leading-none">预约时间</div>
                    <div class="text-base font-black text-slate-900 mt-2">{{ $when->format('d M, h:i A') }}</div>
                    <div class="text-xs font-black text-indigo-700 mt-1 uppercase">
                        {{ $order->schedule_type === 'scheduled' ? '预约' : '即时' }}
                    </div>
                </div>

                <div
                    class="bg-white rounded-[2rem] p-6
                        shadow-[0_10px_24px_rgba(15,23,42,0.06)]
                        border border-slate-200">
                    <div class="text-xs font-black text-slate-500 uppercase tracking-widest leading-none">人数 · 班次</div>
                    <div class="text-base font-black text-slate-900 mt-2">
                        {{ $order->pax ?? '1' }} 人 · <span
                            class="capitalize text-slate-700">{{ $order->shift ?? 'Day' }}</span>
                    </div>
                </div>
            </div>

            @if ($order->note)
                <div
                    class="bg-amber-100/40 rounded-[2rem] p-6 border border-amber-200/60
                        shadow-[0_10px_22px_rgba(120,53,15,0.08)]">
                    <div class="flex items-center gap-2 text-amber-700 mb-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">备注</span>
                    </div>
                    <p class="text-sm font-bold text-amber-900 leading-relaxed">{{ $order->note }}</p>
                </div>
            @endif
        </div>

        {{-- Right: Assignment Panel --}}
        <div class="lg:col-span-5">
            <div
                class="bg-slate-900 rounded-[2.5rem] p-8
                    shadow-[0_22px_60px_rgba(15,23,42,0.25)]
                    sticky top-24">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-white text-lg font-black">派单操作</h3>
                </div>

                @if ($order->status !== 'pending')
                    <div class="mb-6 p-4 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3"> <span
                            class="text-xl">🔒</span>
                        <p class="text-xs font-bold text-slate-400">订单已处理，无法修改派单信息。</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('manager.orders.assign', $order) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- Driver Select --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-200 uppercase tracking-widest ml-1">选择司机</label>
                        <select name="driver_id"
                            class="w-full bg-slate-800/80 border border-white/10 rounded-2xl px-5 py-4 text-sm font-bold text-white
                               focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition disabled:opacity-50"
                            {{ $order->status !== 'pending' ? 'disabled' : '' }}>
                            <option value="">点击选择司机...</option>
                            @foreach ($drivers as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('driver_id', $order->driver_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }} {{ $d->shift ? '(' . ucfirst($d->shift) . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Payment Type Chips --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-200 uppercase tracking-widest ml-1">付款方式</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach ($payOptions as $value => $label)
                                <label class="relative">
                                    <input type="radio" name="payment_type" value="{{ $value }}"
                                        class="peer sr-only"
                                        {{ old('payment_type', $order->payment_type ?? 'cash') === $value ? 'checked' : '' }}
                                        {{ $order->status !== 'pending' ? 'disabled' : '' }}>
                                    <div
                                        class="py-3 text-center rounded-xl border border-white/10
                                           bg-slate-800/80 text-slate-300 text-xs font-black cursor-pointer
                                           peer-checked:bg-white peer-checked:text-slate-900
                                           peer-checked:shadow-[0_16px_34px_rgba(255,255,255,0.10)]
                                           transition-all">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Amount --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-200 uppercase tracking-widest ml-1">收费金额 (RM)</label>
                        <div class="relative">
                            <span
                                class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black text-sm">RM</span>
                            <input type="number" step="0.01" name="amount"
                                value="{{ old('amount', $order->amount) }}"
                                class="w-full bg-slate-800/80 border border-white/10 rounded-2xl pl-12 pr-5 py-4 text-xl font-black text-white
                                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition
                                   placeholder:text-slate-600 disabled:opacity-50"
                                placeholder="0.00" {{ $order->status !== 'pending' ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <button
                        class="w-full py-5 rounded-[1.5rem] bg-indigo-500 hover:bg-indigo-400 text-white font-black tracking-widest text-sm
                           shadow-[0_18px_50px_rgba(99,102,241,0.35)]
                           active:scale-[0.98] transition-all disabled:hidden"
                        {{ $order->status !== 'pending' ? 'disabled' : '' }}>
                        立即派单
                    </button>
                </form>

                @if ($order->driver)
                    <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center font-black text-white
                                   shadow-[0_16px_38px_rgba(99,102,241,0.40)]">
                                {{ substr($order->driver->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-black text-slate-400 uppercase">已指派司机</div>
                                <div class="text-sm font-black text-white mt-0.5 truncate">{{ $order->driver->name }}
                                </div>
                            </div>
                        </div>
                        <a href="tel:{{ $order->driver->phone ?? '' }}"
                            class="h-8 w-8 rounded-full border border-white/12 bg-white/5
                               flex items-center justify-center text-slate-300 hover:text-white transition"
                            aria-label="联系司机">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
