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
        $pickup = $order->pickup ?? '-';
        $dropoff = $order->dropoff ?? '-';
        $note = $order->note;

        $scheduleType = $order->schedule_type ?? 'now'; // now / scheduled
        $scheduledAt = $order->scheduled_at;
        $shift = $order->shift;

        $createdAt = optional($order->created_at)->format('d M Y, h:i A');
        $assignedAt = optional($order->assigned_at)->format('d M Y, h:i A');

        $customerName = optional($order->customer)->name ?? '—';
        $customerPhone = optional($order->customer)->phone ?? '—';

        $driverName = optional($order->driver)->name;
        $driverShift = optional($order->driver)->shift ?? null;

        $managerName = optional($order->manager)->name ?? null;
    @endphp

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        {{-- LEFT --}}
        <div class="min-w-0 flex-1">

            {{-- Row 1: Title --}}
            <div class="flex items-start justify-between gap-4">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 truncate">
                    {{ $orderNo }}
                </h1>

                {{-- Right: Back --}}
                <a href="{{ route('admin.orders.index') }}"
                    class="shrink-0 h-10 px-4 inline-flex items-center gap-2 rounded-2xl
                       bg-white border border-gray-200 text-sm font-extrabold hover:bg-gray-50 transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                    </svg>
                    返回
                </a>
            </div>

            {{-- Row 2: Badges --}}
            <div class="mt-3 flex items-center gap-2 flex-wrap">
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $statusBadge($status) }}">
                    {{ $statusText($status) }}
                </span>

                @if ($paymentType)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payTypeBadge($paymentType) }}">
                        {{ $payTypeText($paymentType) }}
                    </span>
                @endif

                @if ($paymentStatus)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payStatusBadge($paymentStatus) }}">
                        {{ $payStatusText($paymentStatus) }}
                    </span>
                @endif

                @if ($shift)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-gray-100 text-gray-700">
                        {{ strtoupper($shift) }} 班
                    </span>
                @endif

                @if ($scheduleType === 'scheduled' && $scheduledAt)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-700">
                        预约单
                    </span>
                @endif
            </div>

            {{-- Row 3: Meta --}}
            @php
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
            @endphp

            <p class="mt-3 text-sm text-slate-500 font-medium">
                {{ $serviceLabel }} • 创建于 {{ $createdAt }}
            </p>
        </div>

    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- LEFT --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Customer --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5 sm:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-xs font-black tracking-widest uppercase text-slate-400">顾客</div>
                        <div class="mt-2 text-lg font-extrabold text-slate-900">{{ $customerName }}</div>
                        <div class="mt-1 text-sm text-slate-500 font-semibold">
                            电话：<span class="text-slate-900 font-extrabold">{{ $customerPhone }}</span>
                        </div>
                    </div>

                    <div class="h-12 w-12 rounded-2xl bg-black text-white flex items-center justify-center">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Route --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5 sm:p-6">
                <div class="flex items-start justify-between gap-4">

                    <div>
                        <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                            路线
                        </div>

                        <div class="mt-2 text-lg sm:text-xl font-extrabold text-slate-900">
                            {{ $pickup }} → {{ $dropoff }}
                        </div>

                        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm font-semibold text-slate-600">

                            <div>
                                服务：
                                <span class="text-slate-900 font-extrabold">
                                    {{ $service }}
                                </span>
                            </div>

                            {{-- Pax Badge --}}
                            <div
                                class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 border border-gray-200 text-slate-900 text-xs font-extrabold">
                                👤 {{ $pax ?? 1 }} 人
                            </div>

                        </div>

                        <div class="mt-2 text-sm text-slate-600 font-semibold">
                            时间：
                            <span class="text-slate-900 font-extrabold">
                                {{ $scheduleType === 'scheduled' && $scheduledAt ? $scheduledAt->format('d M Y, h:i A') : '立即' }}
                            </span>
                        </div>
                    </div>

                    <div class="h-12 w-12 rounded-2xl bg-gray-100 flex items-center justify-center text-slate-900">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21s7-4.5 7-10a7 7 0 10-14 0c0 5.5 7 10 7 10z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </div>

                </div>

                @if ($note)
                    <div class="mt-5 rounded-2xl bg-gray-50 border border-gray-100 p-4 text-sm text-slate-700">
                        <div class="font-extrabold text-slate-900">备注</div>
                        <div class="mt-1">{{ $note }}</div>
                    </div>
                @endif
            </div>

            {{-- Assign --}}
            <div id="assign" class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-5 border-b border-gray-100">
                    <div class="text-sm font-extrabold text-slate-900">派单（指派司机）</div>
                    <div class="text-xs text-slate-500 font-semibold mt-1">
                        为此订单指派司机并设置付款方式。
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    @if (!$canAssign)
                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 text-sm text-slate-700">
                            目前状态为 <span class="font-extrabold">{{ $statusText($status) }}</span>，派单已锁定，无法更改。
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.orders.assign', $order) }}" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                                {{-- Driver --}}
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                                        司机
                                    </div>

                                    <div class="mt-2">
                                        <select name="driver_id" required
                                            class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-extrabold focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                                            <option value="">选择司机</option>

                                            @foreach ($drivers as $d)
                                                <option value="{{ $d->id }}" @selected((int) $order->driver_id === (int) $d->id)>
                                                    {{ $d->name }}{{ $d->shift ? '（' . ($d->shift === 'day' ? '白班' : '夜班') . '）' : '' }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('driver_id')
                                            <p class="text-xs text-red-600 font-semibold mt-2">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="mt-3 text-xs text-slate-500 font-semibold">
                                        当前司机：
                                        <span class="text-slate-900 font-extrabold">
                                            {{ $driverName ?? '未指派' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Payment --}}
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                                        付款方式
                                    </div>

                                    @php $curPay = strtolower((string)($order->payment_type ?? '')); @endphp

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="cash" class="sr-only peer"
                                                @checked($curPay === 'cash') required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                           peer-checked:bg-black peer-checked:text-white peer-checked:border-black transition">
                                                现金
                                            </span>
                                        </label>

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="credit" class="sr-only peer"
                                                @checked($curPay === 'credit') required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                           peer-checked:bg-rose-50 peer-checked:text-rose-700 peer-checked:border-rose-200 transition">
                                                挂单
                                            </span>
                                        </label>

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="transfer" class="sr-only peer"
                                                @checked($curPay === 'transfer') required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                           peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-200 transition">
                                                转账
                                            </span>
                                        </label>
                                    </div>

                                    @error('payment_type')
                                        <p class="text-xs text-red-600 font-semibold mt-2">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                    <div class="mt-2 text-xs text-slate-500 font-semibold">
                                        司机端会看到此付款方式。
                                    </div>
                                </div>

                                {{-- Amount --}}
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                                        金额（RM）
                                    </div>

                                    <div class="mt-2 relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-slate-400">
                                            RM
                                        </span>

                                        <input type="number" name="amount" step="0.01" min="0"
                                            value="{{ old('amount', $order->amount ?? '') }}" required
                                            class="w-full h-11 rounded-2xl border border-gray-200 bg-white pl-12 pr-4 text-sm font-extrabold
                          focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                                    </div>

                                    @error('amount')
                                        <p class="text-xs text-red-600 font-semibold mt-2">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                    <div class="mt-2 text-xs text-slate-500 font-semibold">
                                        本次订单最终收费金额。
                                    </div>
                                </div>

                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center h-11 px-5 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                                    确认指派
                                </button>

                                <a href="{{ route('admin.orders.index') }}"
                                    class="inline-flex items-center justify-center h-11 px-5 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                                    返回列表
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="space-y-6">
            {{-- Driver --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5 sm:p-6">
                <div class="text-xs font-black tracking-widest uppercase text-slate-400">司机</div>
                <div class="mt-2 text-lg font-extrabold text-slate-900">{{ $driverName ?? '未指派' }}</div>
                <div class="mt-1 text-sm text-slate-500 font-semibold">
                    班次：<span
                        class="text-slate-900 font-extrabold">{{ $driverShift ? ($driverShift === 'day' ? '白班' : '夜班') : '—' }}</span>
                </div>

                <div class="mt-4 rounded-2xl bg-gray-50 border border-gray-100 p-4 text-sm text-slate-700">
                    <div class="font-extrabold text-slate-900">指派时间</div>
                    <div class="mt-1">{{ $assignedAt ?? '—' }}</div>
                </div>
            </div>

            {{-- Manager --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5 sm:p-6">
                <div class="text-xs font-black tracking-widest uppercase text-slate-400">调度员</div>
                <div class="mt-2 text-lg font-extrabold text-slate-900">{{ $managerName ?? '—' }}</div>
                <div class="mt-1 text-sm text-slate-500 font-semibold">
                    （若订单由调度员派单则会显示）
                </div>
            </div>

        </div>
    </div>
@endsection
