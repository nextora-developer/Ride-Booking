@extends('layouts.admin-app')

@section('title', 'Order Details')

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

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between border-b border-slate-50">

        <div class="min-w-0 flex-1">
            {{-- 1. 返回与标签组：分组显示，减少杂乱 --}}
            <div class="flex items-center gap-3 flex-wrap">
                {{-- 极简返回按钮 --}}
                <a href="{{ route('admin.orders.index') }}"
                    class="group h-9 w-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-900 hover:border-slate-900 transition-all">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                    </svg>
                </a>

                {{-- 核心状态：加粗突出 --}}
                <span
                    class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm {{ $statusBadge($status) }}">
                    {{ $status }}
                </span>

                {{-- 分割线 --}}
                <div class="h-4 w-[1px] bg-slate-200 mx-1"></div>

                {{-- 支付与排班信息：更轻量的展示 --}}
                <div class="flex items-center gap-2">
                    @if ($paymentType)
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-black border uppercase tracking-wider {{ $payTypeBadge($paymentType) }}">
                            {{ $paymentType }}
                        </span>
                    @endif

                    @if ($scheduleType === 'scheduled')
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-black bg-indigo-600 text-white uppercase tracking-wider">
                            📅 Scheduled
                        </span>
                    @endif
                </div>
            </div>

            {{-- 2. 核心标题：大字号 + 紧凑字间距 --}}
            <div class="mt-6 flex flex-col md:flex-row md:items-end gap-3 md:gap-6">
                <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-slate-900 uppercase">
                    {{ $orderNo }}
                </h1>

                <div class="flex items-center gap-3 pb-1">
                    <div class="h-5 w-[2px] bg-slate-100 hidden md:block"></div>
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <span
                            class="text-sm font-black text-indigo-600 uppercase tracking-widest">{{ $service }}</span>
                        <span class="hidden md:block text-slate-300">•</span>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-tight">Created
                            {{ $createdAt }}</span>
                    </div>
                </div>
            </div>
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
                        <div class="text-xs font-black tracking-widest uppercase text-slate-400">Customer</div>
                        <div class="mt-2 text-lg font-extrabold text-slate-900">{{ $customerName }}</div>
                        <div class="mt-1 text-sm text-slate-500 font-semibold">
                            Phone: <span class="text-slate-900 font-extrabold">{{ $customerPhone }}</span>
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
                            Route
                        </div>

                        <div class="mt-2 text-lg sm:text-xl font-extrabold text-slate-900">
                            {{ $pickup }} → {{ $dropoff }}
                        </div>

                        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm font-semibold text-slate-600">

                            <div>
                                Service:
                                <span class="text-slate-900 font-extrabold">
                                    {{ $service }}
                                </span>
                            </div>

                            {{-- Pax Badge --}}
                            <div
                                class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 border border-gray-200 text-slate-900 text-xs font-extrabold">
                                👤 {{ $pax ?? 1 }} Pax
                            </div>

                        </div>

                        <div class="mt-2 text-sm text-slate-600 font-semibold">
                            Schedule:
                            <span class="text-slate-900 font-extrabold">
                                {{ $scheduleType === 'scheduled' && $scheduledAt ? $scheduledAt->format('d M Y, h:i A') : 'Now' }}
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
                        <div class="font-extrabold text-slate-900">Note</div>
                        <div class="mt-1">{{ $note }}</div>
                    </div>
                @endif
            </div>

            {{-- Assign --}}
            <div id="assign" class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-5 border-b border-gray-100">
                    <div class="text-sm font-extrabold text-slate-900">Dispatch (Assign Driver)</div>
                    <div class="text-xs text-slate-500 font-semibold mt-1">
                        Assign driver + set payment type for this order.
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    @if (!$canAssign)
                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 text-sm text-slate-700">
                            Dispatch locked because status is <span
                                class="font-extrabold">{{ strtoupper($status) }}</span>.
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.orders.assign', $order) }}" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                                {{-- Driver --}}
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                                        Driver
                                    </div>

                                    <div class="mt-2">
                                        <select name="driver_id" required
                                            class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-extrabold focus:ring-4 focus:ring-black/5 focus:border-black outline-none">

                                            <option value="">Select driver</option>

                                            @foreach ($drivers as $d)
                                                <option value="{{ $d->id }}" @selected((int) $order->driver_id === (int) $d->id)>
                                                    {{ $d->name }}{{ $d->shift ? ' (' . $d->shift . ')' : '' }}
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
                                        Current:
                                        <span class="text-slate-900 font-extrabold">
                                            {{ $driverName ?? 'Not assigned' }}
                                        </span>
                                    </div>
                                </div>


                                {{-- Payment --}}
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                                        Payment Type
                                    </div>

                                    @php $curPay = strtolower((string)($order->payment_type ?? '')); @endphp

                                    <div class="mt-3 flex flex-wrap gap-2">

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="cash" class="sr-only peer"
                                                @checked($curPay === 'cash') required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                           peer-checked:bg-black peer-checked:text-white peer-checked:border-black transition">
                                                Cash
                                            </span>
                                        </label>

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="credit"
                                                class="sr-only peer" @checked($curPay === 'credit') required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                           peer-checked:bg-rose-50 peer-checked:text-rose-700 peer-checked:border-rose-200 transition">
                                                Credit (挂单)
                                            </span>
                                        </label>

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="transfer"
                                                class="sr-only peer" @checked($curPay === 'transfer') required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                           peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-200 transition">
                                                Transfer
                                            </span>
                                        </label>

                                    </div>

                                    @error('payment_type')
                                        <p class="text-xs text-red-600 font-semibold mt-2">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                    <div class="mt-2 text-xs text-slate-500 font-semibold">
                                        Driver will see payment type.
                                    </div>
                                </div>


                                {{-- Amount --}}
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                                        Amount (RM)
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
                                        Final charge for this booking.
                                    </div>
                                </div>

                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center h-11 px-5 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                                    Confirm Assign
                                </button>

                                <a href="{{ route('admin.orders.index') }}"
                                    class="inline-flex items-center justify-center h-11 px-5 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                                    Back to list
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
                <div class="text-xs font-black tracking-widest uppercase text-slate-400">Driver</div>
                <div class="mt-2 text-lg font-extrabold text-slate-900">{{ $driverName ?? 'Not assigned' }}</div>
                <div class="mt-1 text-sm text-slate-500 font-semibold">
                    Shift: <span class="text-slate-900 font-extrabold">{{ $driverShift ?? '—' }}</span>
                </div>

                <div class="mt-4 rounded-2xl bg-gray-50 border border-gray-100 p-4 text-sm text-slate-700">
                    <div class="font-extrabold text-slate-900">Assigned At</div>
                    <div class="mt-1">{{ $assignedAt ?? '—' }}</div>
                </div>
            </div>

            {{-- Manager --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5 sm:p-6">
                <div class="text-xs font-black tracking-widest uppercase text-slate-400">Manager</div>
                <div class="mt-2 text-lg font-extrabold text-slate-900">{{ $managerName ?? '—' }}</div>
                <div class="mt-1 text-sm text-slate-500 font-semibold">
                    (If order was assigned by manager)
                </div>
            </div>
        </div>
    </div>
@endsection
