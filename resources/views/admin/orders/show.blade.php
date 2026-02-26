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

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-black hover:bg-gray-50">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                    </svg>
                    Back
                </a>

                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $statusBadge($status) }}">
                    {{ strtoupper($status) }}
                </span>

                @if ($paymentType)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payTypeBadge($paymentType) }}">
                        {{ strtoupper($paymentType) }}
                    </span>
                @endif

                @if ($paymentStatus)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payStatusBadge($paymentStatus) }}">
                        {{ strtoupper($paymentStatus) }}
                    </span>
                @endif

                @if ($shift)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-gray-100 text-gray-700">
                        {{ strtoupper($shift) }} SHIFT
                    </span>
                @endif

                @if ($scheduleType === 'scheduled' && $scheduledAt)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-700">
                        SCHEDULED
                    </span>
                @endif
            </div>

            <h1 class="mt-3 text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 truncate">
                {{ $orderNo }}
            </h1>

            <p class="mt-2 text-sm text-slate-500 font-medium">
                {{ $service }} • Created {{ $createdAt }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if ($canAssign)
                <a href="#assign"
                    class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                    Assign / Change Driver
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- LEFT --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Route --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5 sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-xs font-black tracking-widest uppercase text-slate-400">Route</div>
                        <div class="mt-2 text-lg sm:text-xl font-extrabold text-slate-900">
                            {{ $pickup }} → {{ $dropoff }}
                        </div>
                        <div class="mt-1 text-sm text-slate-500 font-semibold">
                            Service: <span class="text-slate-900 font-extrabold">{{ $service }}</span>
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
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
                        </svg>
                    </div>
                </div>
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

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                {{-- Driver --}}
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">Driver</div>
                                    <div class="mt-2">
                                        <select name="driver_id" required
                                            class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-extrabold
                                                   focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                                            <option value="">Select driver</option>
                                            @foreach ($drivers as $d)
                                                <option value="{{ $d->id }}" @selected((int) $order->driver_id === (int) $d->id)>
                                                    {{ $d->name }}{{ $d->shift ? ' (' . $d->shift . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('driver_id')
                                            <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-3 text-xs text-slate-500 font-semibold">
                                        Current: <span
                                            class="text-slate-900 font-extrabold">{{ $driverName ?? 'Not assigned' }}</span>
                                    </div>
                                </div>

                                {{-- Payment --}}
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">Payment Type
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
                                            <input type="radio" name="payment_type" value="credit" class="sr-only peer"
                                                @checked($curPay === 'credit') required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white peer-checked:bg-rose-50 peer-checked:text-rose-700 peer-checked:border-rose-200 transition">
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
                                        <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                                    @enderror

                                    <div class="mt-2 text-xs text-slate-500 font-semibold">
                                        Driver will see payment type (Cash/Credit/Transfer).
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

            {{-- Payment --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5 sm:p-6">
                <div class="text-xs font-black tracking-widest uppercase text-slate-400">Payment</div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payTypeBadge($paymentType) }}">
                        {{ strtoupper($paymentType ?? '—') }}
                    </span>

                    @if ($paymentStatus)
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payStatusBadge($paymentStatus) }}">
                            {{ strtoupper($paymentStatus) }}
                        </span>
                    @endif
                </div>

                <div class="mt-3 text-sm text-slate-600 font-semibold">
                    Payment info for driver reference (cash/credit/transfer).
                </div>
            </div>
        </div>
    </div>
@endsection
