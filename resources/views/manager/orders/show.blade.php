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

                {{-- Background decoration --}}
                <div class="absolute top-0 right-0 p-6 opacity-[0.04] pointer-events-none">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                    </svg>
                </div>

                <div class="absolute -left-12 -bottom-12 h-44 w-44 rounded-full bg-slate-100 opacity-70"></div>

                {{-- Route --}}
                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-[10px] top-4 bottom-4 w-px border-l-2 border-dashed border-slate-200"></div>

                    <div class="space-y-7">
                        {{-- Pickup --}}
                        <div class="relative flex items-start gap-4">
                            <div class="relative z-10 shrink-0 pt-1">
                                <div
                                    class="h-5 w-5 rounded-full border-4 border-white bg-slate-300 shadow-[0_8px_18px_rgba(15,23,42,0.08)] flex items-center justify-center">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-700"></div>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5">
                                    上车地点
                                </p>
                                <div class="text-lg font-black text-slate-900 leading-tight break-words">
                                    {{ $order->pickup }}
                                </div>
                            </div>
                        </div>

                        @if (!empty($order->dropoffs))
                            @foreach ($order->dropoffs as $i => $point)
                                <div class="relative flex items-start gap-4">
                                    <div class="relative z-10 shrink-0 pt-1">
                                        <div
                                            class="h-5 w-5 rounded-full border-4 border-white flex items-center justify-center shadow-[0_8px_18px_rgba(15,23,42,0.10)]
                                {{ $loop->last ? 'bg-emerald-500' : 'bg-slate-900' }}">
                                            <div class="h-1.5 w-1.5 rounded-full bg-white"></div>
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5">
                                            {{ $loop->last ? '最终目的地' : '下车点 ' . ($i + 1) }}
                                        </p>
                                        <div
                                            class="{{ $loop->last ? 'text-lg font-black text-slate-900' : 'text-base font-black text-slate-800' }} leading-tight break-words">
                                            {{ $point }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- 旧系统兼容 --}}
                            <div class="relative flex items-start gap-4">
                                <div class="relative z-10 shrink-0 pt-1">
                                    <div
                                        class="h-5 w-5 rounded-full border-4 border-white bg-emerald-500 shadow-[0_8px_18px_rgba(16,185,129,0.20)] flex items-center justify-center">
                                        <div class="h-1.5 w-1.5 rounded-full bg-white"></div>
                                    </div>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5">
                                        目的地
                                    </p>
                                    <div class="text-lg font-black text-slate-900 leading-tight break-words">
                                        {{ $order->dropoff }}
                                    </div>
                                </div>
                            </div>
                        @endif
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

            {{-- Customer Review --}}
            @if ($order->status === 'completed')
                <div
                    class="bg-white rounded-[2rem] p-6 border border-slate-200
                        shadow-[0_10px_24px_rgba(15,23,42,0.06)] overflow-hidden relative">

                    <div class="absolute top-0 right-0 p-5 opacity-[0.05] pointer-events-none text-6xl">★</div>

                    <div class="relative">
                        <div class="flex items-center justify-between gap-4 mb-5">
                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase tracking-widest leading-none">
                                    顾客评价
                                </div>
                                <div class="text-lg font-black text-slate-900 mt-2">
                                    {{ $order->review ? '已提交评价' : '暂未评价' }}
                                </div>
                            </div>

                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-wider
                                {{ $order->review ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $order->review ? '已评价' : '无评价' }}
                            </span>
                        </div>

                        @if ($order->review)
                            <div class="flex items-center gap-1 mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="h-6 w-6 {{ $i <= $order->review->rating ? 'text-yellow-400' : 'text-slate-200' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.538 1.118l-3.377-2.455a1 1 0 00-1.176 0l-3.377 2.455c-.783.57-1.838-.197-1.538-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.098 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z" />
                                    </svg>
                                @endfor

                                <span class="ml-2 text-sm font-black text-slate-700">
                                    {{ $order->review->rating }}/5
                                </span>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
                                    评价内容
                                </div>

                                @if ($order->review->comment)
                                    <p class="text-sm font-bold text-slate-700 leading-relaxed">
                                        “{{ $order->review->comment }}”
                                    </p>
                                @else
                                    <p class="text-sm font-bold text-slate-400">
                                        顾客没有留下文字评价
                                    </p>
                                @endif
                            </div>
                        @else
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                <p class="text-sm font-bold text-slate-500 leading-relaxed">
                                    该订单已完成，但顾客暂时还没有提交评价。
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Assignment Panel --}}
        @php
            $canEditAssign = in_array($order->status, ['pending', 'assigned']);
        @endphp

        <div class="lg:col-span-5">
            <div
                class="bg-slate-900 rounded-[2.5rem] p-8
            shadow-[0_22px_60px_rgba(15,23,42,0.25)]
            sticky top-24">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-white text-lg font-black">
                        {{ $order->driver_id ? '编辑派单' : '派单操作' }}
                    </h3>
                </div>

                @unless ($canEditAssign)
                    <div class="mb-6 p-4 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3">
                        <span class="text-xl">🔒</span>
                        <p class="text-xs font-bold text-slate-400">
                            当前订单状态为 {{ $order->status }}，无法修改派单信息。
                        </p>
                    </div>
                @endunless

                <form method="POST" action="{{ route('manager.orders.assign', $order) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- Driver Select --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-200 uppercase tracking-widest ml-1">选择司机</label>
                        <select name="driver_id"
                            class="w-full bg-slate-800/80 border border-white/10 rounded-2xl px-5 py-4 text-sm font-bold text-white
                           focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition disabled:opacity-50"
                            {{ !$canEditAssign ? 'disabled' : '' }}>
                            <option value="">点击选择司机...</option>
                            @foreach ($drivers as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('driver_id', $order->driver_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }} {{ $d->shift ? '(' . ucfirst($d->shift) . ')' : '' }}
                                </option>
                            @endforeach
                        </select>

                        @error('driver_id')
                            <p class="text-xs text-rose-400 font-bold mt-2">{{ $message }}</p>
                        @enderror
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
                                        {{ !$canEditAssign ? 'disabled' : '' }}>
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

                        @error('payment_type')
                            <p class="text-xs text-rose-400 font-bold mt-2">{{ $message }}</p>
                        @enderror
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
                                placeholder="0.00" {{ !$canEditAssign ? 'disabled' : '' }}>
                        </div>

                        @error('amount')
                            <p class="text-xs text-rose-400 font-bold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-5 rounded-[1.5rem] bg-indigo-500 hover:bg-indigo-400 text-white font-black tracking-widest text-sm
                       shadow-[0_18px_50px_rgba(99,102,241,0.35)]
                       active:scale-[0.98] transition-all disabled:hidden"
                        {{ !$canEditAssign ? 'disabled' : '' }}>
                        {{ $order->driver_id ? '更新派单' : '立即派单' }}
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
                                <div class="text-sm font-black text-white mt-0.5 truncate">
                                    {{ $order->driver->name }}
                                </div>
                                <div class="text-xs text-slate-400 mt-1">
                                    付款方式：{{ $payOptions[$order->payment_type] ?? '-' }} · 金额：RM
                                    {{ number_format((float) $order->amount, 2) }}
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
