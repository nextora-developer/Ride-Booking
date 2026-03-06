@extends('layouts.admin-app')

@section('title', '订单详情')

@section('header')
    @php
        $orderNo = 'ORD-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);

        $status = $order->status ?? 'unassigned';
        $paymentType = $order->payment_type ?? null;
        $paymentStatus = $order->payment_status ?? null;

        $statusBadge = function ($s) {
            $s = strtolower((string) $s);
            return match ($s) {
                'pending' => 'bg-amber-50 text-amber-700',
                'assigned' => 'bg-yellow-50 text-yellow-700',
                'on_the_way' => 'bg-blue-50 text-blue-700',
                'arrived' => 'bg-indigo-50 text-indigo-700',
                'in_trip' => 'bg-violet-50 text-violet-700',
                'completed' => 'bg-green-50 text-green-700',
                'cancelled' => 'bg-gray-100 text-gray-700',
                default => 'bg-gray-100 text-gray-700',
            };
        };

        $payTypeBadge = function ($p) {
            $p = strtolower((string) $p);
            return match ($p) {
                'cash' => 'bg-black text-white',
                'credit' => 'bg-rose-50 text-rose-700',
                'transfer' => 'bg-emerald-50 text-emerald-700',
                default => 'bg-gray-100 text-gray-700',
            };
        };

        $payStatusBadge = function ($p) {
            $p = strtolower((string) $p);
            return match ($p) {
                'unpaid' => 'bg-rose-50 text-rose-700',
                'paid' => 'bg-green-50 text-green-700',
                'pending' => 'bg-yellow-50 text-yellow-700',
                default => 'bg-gray-100 text-gray-700',
            };
        };

        // ✅ 文案映射（状态 / 付款方式 / 付款状态）
        $statusText = function ($s) {
            return match (strtolower((string) $s)) {
                'pending' => '待派单',
                'assigned' => '已指派',
                'on_the_way' => '前往接送',
                'arrived' => '已到起点',
                'in_trip' => '行程中',
                'completed' => '已完成',
                'cancelled', 'canceled' => '已取消',
                'unassigned', 'pending_assign' => '未指派',
                default => strtoupper((string) $s ?: '—'),
            };
        };

        $payTypeText = function ($p) {
            return match (strtolower((string) $p)) {
                'cash' => '现金',
                'credit' => '挂单',
                'transfer' => '转账',
                default => strtoupper((string) $p ?: '—'),
            };
        };

        $payStatusText = function ($p) {
            return match (strtolower((string) $p)) {
                'unpaid' => '未付款',
                'paid' => '已付款',
                'pending' => '处理中',
                default => strtoupper((string) $p ?: '—'),
            };
        };

        $lockedStatuses = ['on_the_way', 'arrived', 'in_trip', 'completed', 'cancelled'];
        $canAssign = !in_array(strtolower((string) $status), $lockedStatuses, true);

        $service = $order->service_type ?? '-';

        $serviceKey = strtolower(trim((string) $service));

        $serviceLabel = match ($serviceKey) {
            'pickup_dropoff' => '接送',
            'charter' => '包车',
            'designated_driver' => '代驾',
            'purchase' => '代购',
            'big_car' => '大车',
            'driver_only' => '司机',
            default => $service,
        };
        $pickup = $order->pickup ?? '-';
        $dropoff = $order->dropoff ?? '-';

        $dropoffs = is_array($order->dropoffs ?? null) ? array_values(array_filter($order->dropoffs)) : [];

        if (empty($dropoffs) && !empty($dropoff) && $dropoff !== '-') {
            $dropoffs = [$dropoff];
        }
        $note = $order->note;

        $scheduleType = $order->schedule_type ?? 'now'; // now / scheduled
        $scheduledAt = $order->scheduled_at;
        $shift = $order->shift;

        $createdAt = optional($order->created_at)->format('d M Y, h:i A');
        $assignedAt = optional($order->assigned_at)->format('d M Y, h:i A');

        $customerName = optional($order->customer)->name ?? '—';
        $customerPhone = optional($order->customer)->phone ?? '—';

        $driverName = optional($order->driver)->full_name;
        $driverShift = optional($order->driver)->shift ?? null;

        $managerName = optional($order->manager)->name ?? null;
    @endphp

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        {{-- LEFT --}}
        <div class="min-w-0 flex-1">

            {{-- Row 1: Title --}}
            <div class="flex items-start justify-between gap-4">

                {{-- Left --}}
                <div class="flex items-center gap-3">

                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 truncate">
                        {{ $orderNo }}
                    </h1>

                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $statusBadge($status) }}">
                        {{ $statusText($status) }}
                    </span>

                </div>

                {{-- Right: Back --}}
                <a href="{{ route('admin.orders.index') }}"
                    class="shrink-0 h-10 px-4 inline-flex items-center gap-2 rounded-2xl bg-white border border-gray-200 text-sm font-extrabold hover:bg-gray-50 transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                    </svg>
                    返回
                </a>

            </div>

            {{-- Row 2: Badges --}}
            <div class="mt-3 flex items-center gap-2 flex-wrap">

                {{-- Service Type --}}
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-indigo-50 text-indigo-700">
                    {{ $serviceLabel }}
                </span>

                {{-- Payment Type --}}
                @if ($paymentType)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payTypeBadge($paymentType) }}">
                        {{ $payTypeText($paymentType) }}
                    </span>
                @endif

                {{-- Schedule Type --}}
                @if ($scheduleType === 'scheduled')
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-700">
                        预约单
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-700">
                        即时单
                    </span>
                @endif

            </div>

        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        {{-- LEFT: 核心信息与操作区 --}}
        <div class="xl:col-span-2 space-y-8">

            {{-- Customer & Route: 组合卡片展示 --}}
            <div class="rounded-[2.5rem] bg-white border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="p-8 sm:p-10">
                    <div class="flex flex-col md:flex-row justify-between gap-8">
                        {{-- 顾客信息 --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <span
                                    class="h-8 w-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <span class="text-xs font-black tracking-[0.2em] uppercase text-slate-400">客户信息</span>
                            </div>
                            <h2 class="text-2xl font-black text-slate-900 leading-tight">{{ $customerName }}</h2>
                            <p class="mt-2 text-slate-500 font-bold flex items-center gap-2">
                                <span class="text-xs uppercase tracking-wider text-slate-400">联系电话:</span>
                                <span class="text-slate-900">{{ $customerPhone }}</span>
                            </p>
                        </div>

                        {{-- 行程时间标签 --}}
                        <div class="text-left md:text-right">
                            <div class="inline-block px-4 py-2 rounded-2xl bg-indigo-50 border border-indigo-100">
                                <div class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-1">
                                    {{ $scheduleType === 'scheduled' ? '预约用车时间' : '即时用车' }}
                                </div>
                                <div class="text-sm font-black text-indigo-900">
                                    {{ $scheduleType === 'scheduled' && $scheduledAt ? $scheduledAt->format('d M Y, h:i A') : '现在 / AS SOON AS POSSIBLE' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-8 h-px bg-slate-100"></div>

                    {{-- 路线展示 --}}
                    <div class="relative">
                        <div class="text-xs font-black tracking-[0.2em] uppercase text-slate-400 mb-6">行程路线</div>

                        <div class="relative">
                            <div class="absolute left-[5px] top-3 bottom-3 w-0.5 bg-slate-100"></div>

                            <div class="space-y-5">
                                {{-- Pickup --}}
                                <div class="relative flex items-start gap-4">
                                    <div
                                        class="relative z-10 mt-1.5 h-3 w-3 rounded-full border-2 border-indigo-500 bg-white shadow-[0_0_0_4px_rgba(99,102,241,0.1)] shrink-0">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-black text-slate-400 uppercase">起点 Pickup</p>
                                        <p class="text-lg font-black text-slate-900 break-words">{{ $pickup }}</p>
                                    </div>
                                </div>

                                {{-- Dropoffs --}}
                                @if (!empty($dropoffs))
                                    @foreach ($dropoffs as $i => $point)
                                        <div class="relative flex items-start gap-4">
                                            <div
                                                class="relative z-10 mt-1.5 h-3 w-3 rounded-full shrink-0
                            {{ $loop->last ? 'bg-emerald-500 shadow-[0_0_0_4px_rgba(16,185,129,0.12)]' : 'bg-slate-900 shadow-lg shadow-slate-200' }}">
                                            </div>

                                            <div class="min-w-0">
                                                <p class="text-xs font-black text-slate-400 uppercase">
                                                    {{ $loop->last ? '终点 Dropoff' : '下车点 ' . ($i + 1) }}
                                                </p>
                                                <p class="text-lg font-black text-slate-900 break-words">
                                                    {{ $point }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="relative flex items-start gap-4">
                                        <div
                                            class="relative z-10 mt-1.5 h-3 w-3 rounded-full bg-emerald-500 shadow-[0_0_0_4px_rgba(16,185,129,0.12)] shrink-0">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-black text-slate-400 uppercase">终点 Dropoff</p>
                                            <p class="text-lg font-black text-slate-900 break-words">{{ $dropoff }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 服务标签 --}}
                    <div class="mt-8 flex flex-wrap gap-3">
                        <span
                            class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-black uppercase tracking-wider">
                            ✨ {{ $serviceLabel }}
                        </span>
                        <span
                            class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-black uppercase tracking-wider">
                            👤 {{ $pax ?? 1 }} 人乘坐
                        </span>
                    </div>

                    @if ($note)
                        <div class="mt-8 p-5 rounded-2xl bg-amber-50/50 border border-amber-100/50 relative">
                            <span
                                class="absolute -top-3 left-4 px-2 bg-white text-xs font-black text-amber-600 uppercase">客户备注</span>
                            <p class="text-sm font-bold text-amber-900">"{{ $note }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Assign: 派单操作 --}}
            <div id="assign"
                class="rounded-[2.5rem] bg-white border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">指派司机与计费</h3>
                        <p class="text-[11px] text-slate-400 font-bold mt-1">请核对司机空档及金额后再确认</p>
                    </div>
                    @if (!$canAssign)
                        <span
                            class="px-3 py-1 rounded-lg bg-rose-100 text-rose-600 text-[10px] font-black uppercase">订单已锁定</span>
                    @endif
                </div>

                <div class="p-8">
                    @if (!$canAssign)
                        <div
                            class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-sm font-bold text-slate-500">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            当前状态为 {{ $statusText($status) }}，派单信息已归档，无法修改。
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.orders.assign', $order) }}" class="space-y-8">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {{-- Driver Select --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">选择承运司机</label>
                                    <div class="relative group">
                                        <select name="driver_id" required
                                            class="appearance-none w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/30 px-5 text-sm font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all outline-none cursor-pointer">
                                            <option value="">点击选择司机...</option>
                                            @foreach ($drivers as $d)
                                                <option value="{{ $d->id }}" @selected((int) $order->driver_id === (int) $d->id)>
                                                    {{ $d->name }}
                                                    {{ $d->shift ? '(' . ($d->shift === 'day' ? '白班' : '夜班') . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        </div>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 ml-1 italic">当前指派: <span
                                            class="text-indigo-600">{{ $driverName ?? '未指派' }}</span></p>
                                </div>

                                {{-- Payment Type --}}
                                <div class="space-y-2 lg:col-span-1">
                                    <label
                                        class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">结算方式</label>
                                    <div class="flex p-1 bg-slate-100 rounded-[1.25rem] h-14 items-center">
                                        @php $curPay = strtolower((string)($order->payment_type ?? 'cash')); @endphp
                                        @foreach (['cash' => '现金', 'credit' => '挂单', 'transfer' => '转账'] as $val => $label)
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="payment_type" value="{{ $val }}"
                                                    class="sr-only peer" @checked($curPay === $val)>
                                                <span
                                                    class="flex items-center justify-center h-11 text-xs font-black rounded-xl transition-all
                                                    peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-indigo-600 text-slate-500">
                                                    {{ $label }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Amount Input --}}
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">订单金额
                                        (RM)</label>
                                    <div class="relative">
                                        <div
                                            class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-slate-300 pointer-events-none">
                                            RM</div>
                                        <input type="number" name="amount" step="0.01" min="0"
                                            value="{{ old('amount', $order->amount ?? '') }}" required
                                            class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/30 pl-14 pr-5 text-lg font-black text-slate-900 focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none">
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-slate-50">
                                <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center h-14 px-10 rounded-2xl bg-slate-900 text-white text-sm font-black uppercase tracking-widest hover:bg-black hover:shadow-xl hover:shadow-slate-200 transition-all active:scale-95">
                                    确认指派此订单
                                </button>
                                <a href="{{ route('admin.orders.index') }}"
                                    class="w-full sm:w-auto inline-flex items-center justify-center h-14 px-8 rounded-2xl bg-white border border-slate-200 text-slate-400 text-sm font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                                    取消并返回
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: 统计与状态摘要 --}}
        <div class="space-y-6">

            {{-- 财务摘要 --}}
            <div class="rounded-[2rem] bg-slate-900 p-8 shadow-xl shadow-slate-200 relative overflow-hidden group">
                <div
                    class="absolute -right-6 -bottom-6 h-32 w-32 bg-white/5 rounded-full group-hover:scale-110 transition-transform duration-700">
                </div>

                <div class="relative z-10">
                    <span class="text-[10px] font-black tracking-[0.2em] uppercase text-slate-400">Payment Summary</span>
                    <div class="mt-4 flex items-baseline gap-2 text-white font-black text-4xl tracking-tighter">
                        <span class="text-xl text-slate-500 font-bold tracking-normal">RM</span>
                        {{ number_format($order->amount ?? 0, 2) }}
                    </div>
                    <div class="mt-6 flex items-center justify-between py-3 border-t border-white/10">
                        <span class="text-xs font-bold text-slate-400">付款方式</span>
                        <span
                            class="text-xs font-black text-emerald-400 uppercase tracking-widest">{{ $payTypeText($paymentType) ?? '未设置' }}</span>
                    </div>
                </div>
            </div>

            {{-- 司机状态 --}}
            <div class="rounded-[2rem] bg-white border border-slate-100 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <span class="text-xs font-black tracking-[0.2em] uppercase text-slate-400">司机指派</span>
                    <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                </div>

                <div class="flex items-center gap-4 mb-6">
                    <div class="h-14 w-14 rounded-2xl bg-slate-50 flex items-center justify-center text-2xl shadow-inner">
                        {{ $driverName ? '👨‍✈️' : '⏳' }}
                    </div>
                    <div>
                        <div class="text-lg font-black text-slate-900 leading-none">{{ $driverName ?? '等待指派中' }}</div>
                        <div class="mt-2 text-xs font-black text-slate-400 uppercase tracking-wider">
                            班次：<span
                                class="text-slate-900">{{ $driverShift ? ($driverShift === 'day' ? '白班' : '夜班') : '—' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50/50 border border-slate-50">
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">指派时间</div>
                    <div class="text-sm font-bold text-slate-700">{{ $assignedAt ?? '尚未指派' }}</div>
                </div>
            </div>

            {{-- 经办人 --}}
            <div class="rounded-[2rem] bg-white border border-slate-100 p-6 shadow-sm flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">调度员</div>
                    <div class="text-sm font-black text-slate-900">{{ $managerName ?? '系统自动' }}</div>
                </div>
            </div>

            {{-- 顾客评价 --}}
            <div class="rounded-[2rem] bg-white border border-slate-100 p-6 shadow-sm">

                <div class="flex items-center justify-between mb-4">
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">
                        顾客评价
                    </div>

                    @if ($order->review)
                        <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase">
                            已评价
                        </span>
                    @else
                        <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-black uppercase">
                            暂无评价
                        </span>
                    @endif
                </div>

                @if ($order->review)

                    {{-- 星级 --}}
                    <div class="flex items-center gap-1 mb-4">

                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="h-5 w-5 {{ $i <= $order->review->rating ? 'text-yellow-400' : 'text-slate-200' }}"
                                fill="currentColor" viewBox="0 0 20 20">

                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.538 1.118l-3.377-2.455a1 1 0 00-1.176 0l-3.377 2.455c-.783.57-1.838-.197-1.538-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.098 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z" />

                            </svg>
                        @endfor

                        <span class="ml-2 text-sm font-black text-slate-700">
                            {{ $order->review->rating }}/5
                        </span>

                    </div>

                    {{-- 评论 --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">

                        @if ($order->review->comment)
                            <p class="text-sm font-bold text-slate-700 leading-relaxed">
                                "{{ $order->review->comment }}"
                            </p>
                        @else
                            <p class="text-sm font-bold text-slate-400">
                                顾客没有留下文字评价
                            </p>
                        @endif

                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-sm font-bold text-slate-400">
                            该订单尚未收到顾客评价。
                        </p>
                    </div>

                @endif

            </div>

        </div>
    </div>
@endsection
