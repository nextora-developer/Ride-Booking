@extends('layouts.admin-app')

@section('title', '订单')

@section('header')
    <div class="relative px-2">

        {{-- Mobile Header --}}
        <div class="md:hidden flex items-center justify-between h-14">

            <div>
                <h1 class="text-lg font-black text-slate-900">
                    订单
                </h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    派单面板
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-black text-white shadow active:scale-90 transition-transform"
                title="刷新">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
            </a>

        </div>

        {{-- Desktop Header --}}
        <div class="hidden md:flex items-end justify-between">

            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                    订单
                </h1>

                <p class="mt-2 text-sm text-slate-500 font-medium">
                    查看订单并指派司机（现金 / 挂单 / 转账）。
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-2xl bg-black text-white text-sm font-bold hover:bg-slate-900 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
                刷新
            </a>

        </div>

    </div>
@endsection

@section('content')
    @php
        // ✅ helpers (status / payment badge)
        $statusBadge = function ($s) {
            $s = strtolower((string) $s);
            return match ($s) {
                'unassigned', 'pending_assign' => 'bg-rose-50 text-rose-700',
                'assigned' => 'bg-yellow-50 text-yellow-700',
                'ongoing', 'in_progress' => 'bg-blue-50 text-blue-700',
                'completed', 'done' => 'bg-green-50 text-green-700',
                'cancelled', 'canceled' => 'bg-gray-100 text-gray-700',
                default => 'bg-gray-100 text-gray-700',
            };
        };

        $payBadge = function ($p) {
            $p = strtolower((string) $p);
            return match ($p) {
                'cash' => 'bg-black text-white',
                'credit' => 'bg-rose-50 text-rose-700',
                'transfer' => 'bg-emerald-50 text-emerald-700',
                default => 'bg-gray-100 text-gray-700',
            };
        };

        // ✅ 文案映射（状态/付款/按钮）
        $statusText = function ($s) {
            return match (strtolower((string) $s)) {
                'unassigned', 'pending_assign', 'pending' => '待派单',
                'assigned' => '已指派',
                'on_the_way' => '前往接送',
                'arrived' => '已到起点',
                'in_trip' => '行程中',
                'ongoing', 'in_progress' => '进行中',
                'completed', 'done' => '已完成',
                'cancelled', 'canceled' => '已取消',
                default => strtoupper((string) $s ?: '—'),
            };
        };

        $payText = function ($p) {
            return match (strtolower((string) $p)) {
                'cash' => '现金',
                'credit' => '挂单',
                'transfer' => '转账',
                default => strtoupper((string) $p ?: '—'),
            };
        };
    @endphp

    @php
        $active = fn($s) => ($status ?? null) === $s;
        $pill = 'px-3 py-1.5 rounded-full text-xs font-black transition';
    @endphp

    @php
        $qs = [
            'q' => $q ?? null,
            'payment_type' => $payment_type ?? null,
            'shift' => $shift ?? null,
            'from' => $from ?? null,
            'to' => $to ?? null,
        ];
    @endphp

    @php
        $label = 'text-[10px] font-black tracking-widest uppercase text-slate-400 ml-1';
        $ctrl = 'w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-semibold
              focus:ring-4 focus:ring-black/5 focus:border-black outline-none';
    @endphp

    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">

                {{-- 🔍 Search --}}
                <div class="sm:col-span-2 lg:col-span-5">
                    <div class="{{ $label }}">搜索</div>
                    <div class="relative">
                        <input name="q" value="{{ $q ?? '' }}" type="text" placeholder="订单 / 顾客 / 路线..."
                            class="{{ $ctrl }} pr-10">
                        <svg class="h-5 w-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                            <circle cx="11" cy="11" r="7" />
                        </svg>
                    </div>
                </div>

                {{-- Status --}}
                <div class="lg:col-span-2">
                    <div class="{{ $label }}">状态</div>
                    <select name="status" class="{{ $ctrl }}">
                        <option value="">全部</option>
                        <option value="pending" @selected(($status ?? '') === 'pending')>待派单</option>
                        <option value="assigned" @selected(($status ?? '') === 'assigned')>已指派</option>
                        <option value="on_the_way" @selected(($status ?? '') === 'on_the_way')>前往接送</option>
                        <option value="in_trip" @selected(($status ?? '') === 'in_trip')>行程中</option>
                        <option value="completed" @selected(($status ?? '') === 'completed')>已完成</option>
                    </select>
                </div>

                {{-- Payment --}}
                <div class="lg:col-span-2">
                    <div class="{{ $label }}">付款</div>
                    <select name="payment_type" class="{{ $ctrl }}">
                        <option value="">全部付款</option>
                        <option value="cash" @selected(($payment_type ?? '') === 'cash')>现金</option>
                        <option value="credit" @selected(($payment_type ?? '') === 'credit')>挂单</option>
                        <option value="transfer" @selected(($payment_type ?? '') === 'transfer')>转账</option>
                    </select>
                </div>

                {{-- Shift --}}
                <div class="lg:col-span-3">
                    <div class="{{ $label }}">班次</div>
                    <select name="shift" class="{{ $ctrl }}">
                        <option value="">全部班次</option>
                        <option value="day" @selected(($shift ?? '') === 'day')>白班</option>
                        <option value="night" @selected(($shift ?? '') === 'night')>夜班</option>
                    </select>
                </div>

                {{-- From --}}
                <div class="lg:col-span-3">
                    <div class="{{ $label }}">开始日期</div>
                    <input type="date" name="from" value="{{ $from ?? '' }}" class="{{ $ctrl }}">
                </div>

                {{-- To --}}
                <div class="lg:col-span-3">
                    <div class="{{ $label }}">结束日期</div>
                    <input type="date" name="to" value="{{ $to ?? '' }}" class="{{ $ctrl }}">
                </div>

                {{-- Buttons --}}
                <div class="sm:col-span-2 lg:col-span-6 flex items-end justify-end gap-2">
                    <button
                        class="h-11 px-6 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                        筛选
                    </button>

                    @if (!empty($q) || !empty($status) || !empty($payment_type) || !empty($shift) || !empty($from) || !empty($to))
                        <a href="{{ route('admin.orders.index') }}"
                            class="h-11 inline-flex items-center justify-center px-6 rounded-2xl border border-gray-200 bg-white text-sm font-extrabold hover:bg-gray-50 transition">
                            重置
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- Orders list --}}
    <div class="mt-6 space-y-4">
        @forelse($orders as $order)
            @php
                $orderNo = $order->order_no ?? ($order->booking_no ?? '#' . $order->id);
                $s = $order->status ?? 'unassigned';
                $payment = $order->payment_type ?? null;
                $service = $order->service_type ?? ($order->type ?? '服务');
                $fromLoc = $order->pickup ?? ($order->from ?? '-');
                $dropoffs = is_array($order->dropoffs ?? null) ? array_values(array_filter($order->dropoffs)) : [];

                if (empty($dropoffs) && !empty($order->dropoff)) {
                    $dropoffs = [$order->dropoff];
                }

                $routePoints = array_filter(array_merge([$fromLoc], $dropoffs));
                $routeText = !empty($routePoints) ? implode(' → ', $routePoints) : '-';
                $when = optional($order->created_at)->format('d M Y, h:i A');

                $driverName = optional($order->driver)->name ?? ($order->driver_name ?? null);
                $canAssign = in_array(
                    strtolower((string) $s),
                    ['unassigned', 'pending_assign', 'assigned', 'pending'],
                    true,
                );
            @endphp

            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden" x-data="{ openAssign: false }">

                <div class="p-4 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="text-lg font-extrabold text-slate-900">{{ $orderNo }}</div>

                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $statusBadge($s) }}">
                                    {{ $statusText($s) }}
                                </span>

                                @if ($payment)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payBadge($payment) }}">
                                        {{ $payText($payment) }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-2 text-sm text-slate-600 font-semibold">

                                @php
                                    $serviceLabel = match ($service) {
                                        'pickup_dropoff' => '接送',
                                        'charter' => '包车',
                                        'designated_driver' => '代驾',
                                        'purchase' => '代购',
                                        'big_car' => '大车',
                                        'driver_only' => '司机',
                                        default => $service,
                                    };
                                @endphp

                                <span class="font-extrabold text-slate-900">{{ $serviceLabel }}</span>
                                <span class="text-slate-400">•</span>
                                <span class="truncate inline-block max-w-full align-bottom">
                                    {{ $routeText }}
                                </span>
                            </div>

                            <div class="mt-2 text-xs text-slate-500 font-semibold">
                                {{ $when }}
                                @if ($driverName)
                                    <span class="mx-2 text-slate-300">•</span>
                                    司机：<span class="text-slate-900 font-extrabold">{{ $driverName }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($canAssign)
                                <button @click="openAssign = !openAssign"
                                    class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                                    {{ strtolower((string) $s) === 'assigned' ? '更换司机' : '指派司机' }}
                                </button>
                            @endif

                            @if (Route::has('admin.orders.show'))
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                                    查看详情
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Assign Panel --}}
                @if ($canAssign)
                    <div x-cloak x-show="openAssign" x-transition
                        class="border-t border-gray-100 bg-gray-50/60 p-4 sm:p-6">
                        <form method="POST" action="{{ route('admin.orders.assign', $order) }}">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                {{-- Driver --}}
                                <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">选择司机</div>
                                    <div class="mt-2">
                                        <select name="driver_id" required
                                            class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-extrabold focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                                            <option value="">请选择司机</option>
                                            @foreach ($drivers as $d)
                                                <option value="{{ $d->id }}">
                                                    {{ $d->name }}{{ $d->shift ? '（' . ($d->shift === 'day' ? '白班' : '夜班') . '）' : '' }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="mt-2 text-xs text-slate-500 font-semibold">
                                            建议选择对应班次的司机（如有）。
                                        </div>

                                        @error('driver_id')
                                            <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Payment type --}}
                                <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">付款方式</div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="cash"
                                                class="sr-only peer" required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                                                       peer-checked:bg-black peer-checked:text-white peer-checked:border-black transition">
                                                现金
                                            </span>
                                        </label>

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="credit"
                                                class="sr-only peer" required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                                                       peer-checked:bg-rose-50 peer-checked:text-rose-700 peer-checked:border-rose-200 transition">
                                                挂单
                                            </span>
                                        </label>

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="transfer"
                                                class="sr-only peer" required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                                                       peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-200 transition">
                                                转账
                                            </span>
                                        </label>
                                    </div>

                                    <div class="mt-2 text-xs text-slate-500 font-semibold">
                                        司机端会看到此付款方式。
                                    </div>

                                    @error('payment_type')
                                        <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Amount --}}
                                <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">金额（RM）</div>

                                    <div class="mt-2 relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-slate-400">RM</span>
                                        <input type="number" name="amount" step="0.01" min="0" required
                                            value="{{ old('amount', $order->amount ?? '') }}"
                                            class="w-full h-11 rounded-2xl border border-gray-200 bg-white pl-12 pr-4 text-sm font-extrabold
                                                   focus:ring-4 focus:ring-black/5 focus:border-black outline-none"
                                            placeholder="0.00">
                                    </div>

                                    <div class="mt-2 text-xs text-slate-500 font-semibold">
                                        本次订单最终收费金额。
                                    </div>

                                    @error('amount')
                                        <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Confirm --}}
                                <div class="rounded-2xl bg-white border border-gray-100 p-4 flex flex-col justify-between">
                                    <div>
                                        <div class="text-xs font-black tracking-widest uppercase text-slate-400">确认</div>
                                        <div class="mt-2 text-sm text-slate-600 font-semibold">
                                            指派司机并锁定付款方式。
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center gap-2">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                                            确认指派
                                        </button>

                                        <button type="button" @click="openAssign=false"
                                            class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                                            取消
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="rounded-3xl bg-white border border-gray-100 p-10 text-center">
                <div class="text-2xl font-extrabold text-slate-900">暂无订单</div>
                <div class="mt-2 text-sm text-slate-500 font-medium">当顾客下单后，订单会显示在这里。</div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection
