@extends('layouts.admin-app')

@section('title', 'Orders')

@section('header')
    <div class="relative px-2">

        {{-- Mobile Header --}}
        <div class="md:hidden flex items-center justify-between h-14">

            <div>
                <h1 class="text-lg font-black text-slate-900">
                    Orders
                </h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Dispatch Panel
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-black text-white shadow active:scale-90 transition-transform">
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
                    Orders
                </h1>

                <p class="mt-2 text-sm text-slate-500 font-medium">
                    Review bookings and assign drivers (Cash / Credit / Transfer).
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-2xl bg-black text-white text-sm font-bold hover:bg-slate-900 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
                Refresh
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
        ];
    @endphp

    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-4 sm:p-6 mb-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-black tracking-widest uppercase text-slate-400">Quick filter</span>

                <a href="{{ route('admin.orders.index', array_filter(array_merge($qs, ['status' => 'pending']))) }}"
                    class="{{ $pill }} {{ $active('pending') ? 'bg-amber-100 text-amber-800' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Pending
                </a>

                <a href="{{ route('admin.orders.index', array_filter(array_merge($qs, ['status' => 'assigned']))) }}"
                    class="{{ $pill }} {{ $active('assigned') ? 'bg-yellow-100 text-yellow-800' : 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' }}">
                    Assigned
                </a>

                <a href="{{ route('admin.orders.index', array_filter(array_merge($qs, ['status' => 'on_the_way']))) }}"
                    class="{{ $pill }} {{ $active('on_the_way') ? 'bg-blue-100 text-blue-800' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    On The Way
                </a>

                <a href="{{ route('admin.orders.index', array_filter(array_merge($qs, ['status' => 'in_trip']))) }}"
                    class="{{ $pill }} {{ $active('in_trip') ? 'bg-violet-100 text-violet-800' : 'bg-violet-50 text-violet-700 hover:bg-violet-100' }}">
                    In Trip
                </a>

                <a href="{{ route('admin.orders.index', array_filter(array_merge($qs, ['status' => 'completed']))) }}"
                    class="{{ $pill }} {{ $active('completed') ? 'bg-green-100 text-green-800' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                    Completed
                </a>

                <a href="{{ route('admin.orders.index', array_filter($qs)) }}"
                    class="{{ $pill }} {{ empty($status) ? 'bg-gray-200 text-gray-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All
                </a>
            </div>

            <form method="GET" action="{{ route('admin.orders.index') }}"
                class="flex items-center gap-2 w-full lg:w-auto">
                @if (!empty($status))
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif

                {{-- Payment Type --}}
                <div class="w-full sm:w-44">
                    <select name="payment_type"
                        class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-semibold
               focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                        <option value="">All Payment</option>
                        <option value="cash" @selected(($payment_type ?? '') === 'cash')>Cash</option>
                        <option value="credit" @selected(($payment_type ?? '') === 'credit')>Credit</option>
                        <option value="transfer" @selected(($payment_type ?? '') === 'transfer')>Transfer</option>
                    </select>
                </div>

                {{-- Shift --}}
                <div class="w-full sm:w-44">
                    <select name="shift"
                        class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-semibold
               focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                        <option value="">All Shift</option>
                        <option value="day" @selected(($shift ?? '') === 'day')>Day</option>
                        <option value="night" @selected(($shift ?? '') === 'night')>Night</option>
                    </select>
                </div>

                <div class="relative w-full sm:w-80">
                    <input name="q" value="{{ $q ?? '' }}" type="text"
                        placeholder="Search order no / customer / route..."
                        class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 pr-10 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                    <svg class="h-5 w-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                        <circle cx="11" cy="11" r="7" />
                    </svg>
                </div>

                <button
                    class="h-11 px-5 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                    Filter
                </button>

                @if (!empty($q) || !empty($status) || !empty($payment_type) || !empty($shift))
                    <a href="{{ route('admin.orders.index') }}"
                        class="py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm font-extrabold hover:bg-gray-50 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Orders list --}}
    <div class="mt-6 space-y-4">
        @forelse($orders as $order)
            @php
                // 你按你的字段改：
                $orderNo = $order->order_no ?? ($order->booking_no ?? '#' . $order->id);
                $status = $order->status ?? 'unassigned';
                $payment = $order->payment_type ?? null;
                $service = $order->service_type ?? ($order->type ?? 'Service');
                $from = $order->pickup ?? ($order->from ?? '-');
                $to = $order->dropoff ?? ($order->to ?? '-');
                $when = optional($order->created_at)->format('d M Y, h:i A');

                $driverName = optional($order->driver)->name ?? ($order->driver_name ?? null);
                $canAssign = in_array(strtolower((string) $status), ['unassigned', 'pending_assign', 'assigned'], true);
            @endphp

            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden" x-data="{ openAssign: false }">

                <div class="p-4 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="text-lg font-extrabold text-slate-900">{{ $orderNo }}</div>

                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $statusBadge($status) }}">
                                    {{ ucfirst(str_replace('_', ' ', (string) $status)) }}
                                </span>

                                @if ($payment)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payBadge($payment) }}">
                                        {{ strtoupper($payment) }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-2 text-sm text-slate-600 font-semibold">
                                <span class="font-extrabold text-slate-900">{{ $service }}</span>
                                <span class="text-slate-400">•</span>
                                {{ $from }} → {{ $to }}
                            </div>

                            <div class="mt-2 text-xs text-slate-500 font-semibold">
                                {{ $when }}
                                @if ($driverName)
                                    <span class="mx-2 text-slate-300">•</span>
                                    Driver: <span class="text-slate-900 font-extrabold">{{ $driverName }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($canAssign)
                                <button @click="openAssign = !openAssign"
                                    class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                                    {{ strtolower((string) $status) === 'assigned' ? 'Change Driver' : 'Assign' }}
                                </button>
                            @endif

                            @if (Route::has('admin.orders.show'))
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                                    View
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
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">Driver</div>
                                    <div class="mt-2">
                                        <select name="driver_id" required
                                            class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-extrabold focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                                            <option value="">Select driver</option>
                                            @foreach ($drivers as $d)
                                                <option value="{{ $d->id }}">
                                                    {{ $d->name }}{{ $d->shift ? ' (' . $d->shift . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="mt-2 text-xs text-slate-500 font-semibold">
                                            Choose the driver (matching shift if applicable).
                                        </div>

                                        @error('driver_id')
                                            <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Payment type --}}
                                <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">Payment Type
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="cash"
                                                class="sr-only peer" required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                                                        peer-checked:bg-black peer-checked:text-white peer-checked:border-black transition">
                                                Cash
                                            </span>
                                        </label>

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="credit"
                                                class="sr-only peer" required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                                                        peer-checked:bg-rose-50 peer-checked:text-rose-700 peer-checked:border-rose-200 transition">
                                                Credit (挂单)
                                            </span>
                                        </label>

                                        <label class="cursor-pointer">
                                            <input type="radio" name="payment_type" value="transfer"
                                                class="sr-only peer" required>
                                            <span
                                                class="inline-flex items-center px-3 py-2 rounded-2xl text-sm font-extrabold border border-gray-200 bg-white
                                                        peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-200 transition">
                                                Transfer
                                            </span>
                                        </label>
                                    </div>

                                    <div class="mt-2 text-xs text-slate-500 font-semibold">
                                        Driver will see this payment type.
                                    </div>

                                    @error('payment_type')
                                        <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Amount --}}
                                <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">Amount (RM)
                                    </div>

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
                                        Final charge for this booking.
                                    </div>

                                    @error('amount')
                                        <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Confirm (full width on desktop) --}}
                                <div class="rounded-2xl bg-white border border-gray-100 p-4 flex flex-col justify-between">
                                    <div>
                                        <div class="text-xs font-black tracking-widest uppercase text-slate-400">Confirm
                                        </div>
                                        <div class="mt-2 text-sm text-slate-600 font-semibold">
                                            Assign driver and lock payment type for this order.
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center gap-2">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                                            Confirm Assign
                                        </button>

                                        <button type="button" @click="openAssign=false"
                                            class="inline-flex items-center justify-center h-11 px-4 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                                            Cancel
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
                <div class="text-2xl font-extrabold text-slate-900">No orders yet</div>
                <div class="mt-2 text-sm text-slate-500 font-medium">When customer books, orders will appear here.</div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection
