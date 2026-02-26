@extends('layouts.admin-app')

@section('title', 'Boss Dashboard')

@section('header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>

            <h1 class="mt-3 text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">
                Control Center
            </h1>

            <p class="mt-2 text-sm text-slate-500 font-medium">
                Overview of orders, dispatch, drivers and payment types (Cash / Credit / Transfer).
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-2xl bg-black text-white text-sm font-bold hover:bg-slate-900 transition">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                </svg>
                Refresh
            </a>

            <a href="#"
                class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-bold hover:bg-gray-50 transition">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h16" />
                </svg>
                Dispatch Queue
            </a>
        </div>
    </div>
@endsection

@section('content')

    {{-- Top Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-3xl bg-white border border-gray-100 p-6 shadow-sm">
            <div class="text-xs font-black tracking-widest uppercase text-slate-400">Total Orders</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ number_format($total30) }}</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">Last 30 days</div>
        </div>

        <div class="rounded-3xl bg-white border border-gray-100 p-6 shadow-sm">
            <div class="text-xs font-black tracking-widest uppercase text-slate-400">Pending</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ number_format($pending) }}</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">Need dispatch</div>
        </div>

        <div class="rounded-3xl bg-white border border-gray-100 p-6 shadow-sm">
            <div class="text-xs font-black tracking-widest uppercase text-slate-400">Active Trips</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ number_format($active) }}</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">Assigned / On the way / In trip</div>
        </div>

        <div class="rounded-3xl bg-white border border-gray-100 p-6 shadow-sm">
            <div class="text-xs font-black tracking-widest uppercase text-slate-400">Completed Today</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ number_format($todayCompleted) }}</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">Count (no fare yet)</div>
        </div>
    </div>

    {{-- Operations Row --}}
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="rounded-3xl bg-white border border-gray-100 p-6 shadow-sm">
            <div class="text-xs font-black tracking-widest uppercase text-slate-400">Drivers (Total)</div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $driversTotal }}</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">Registered drivers</div>
        </div>

        <div class="rounded-3xl bg-white border border-gray-100 p-6 shadow-sm">
            <div class="text-xs font-black tracking-widest uppercase text-slate-400">Day Shift</div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $driversDay }}</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">On duty</div>
        </div>

        <div class="rounded-3xl bg-white border border-gray-100 p-6 shadow-sm">
            <div class="text-xs font-black tracking-widest uppercase text-slate-400">Night Shift</div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $driversNight }}</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">On duty</div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="mt-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Dispatch Queue / Latest Orders --}}
        <div class="xl:col-span-2 rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <div class="text-sm font-extrabold text-slate-900">Latest Bookings</div>
                    <div class="text-xs text-slate-500 font-medium mt-1">
                        Monitor dispatch status + payment types
                    </div>
                </div>
                <div class="flex items-center gap-2">

                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex items-center justify-center h-10 px-4 rounded-2xl bg-black text-white text-sm font-bold hover:bg-slate-900 transition">
                        View All
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-black tracking-widest uppercase text-slate-400">
                            <th class="px-6 py-4">Booking</th>
                            <th class="px-6 py-4">Service</th>
                            <th class="px-6 py-4">Route</th>
                            <th class="px-6 py-4">Payment</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    @php
                        $badge = function ($status) {
                            return match (strtolower((string) $status)) {
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

                        $payBadge = function ($pay) {
                            return match (strtolower((string) $pay)) {
                                'cash' => 'bg-black text-white',
                                'credit' => 'bg-rose-50 text-rose-700',
                                'transfer' => 'bg-emerald-50 text-emerald-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        };

                        $orderNo = fn($id) => 'ORD-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
                    @endphp
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($latestOrders as $o)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-slate-900">{{ $orderNo($o->id) }}</div>
                                    <div class="text-xs text-slate-500 font-medium mt-1">
                                        {{ optional($o->created_at)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $o->service_type ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-900 font-semibold">{{ $o->pickup ?? '-' }} →
                                        {{ $o->dropoff ?? '-' }}</div>
                                    <div class="text-xs text-slate-500 font-medium mt-1">
                                        Customer: {{ optional($o->customer)->name ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payBadge($o->payment_type) }}">
                                        {{ strtoupper($o->payment_type ?? '—') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $badge($o->status) }}">
                                        {{ strtoupper($o->status ?? '—') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $o) }}"
                                        class="inline-flex items-center justify-center h-9 px-3 rounded-xl bg-white border border-gray-200 text-sm font-bold hover:bg-gray-50 transition">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="space-y-6">
            {{-- Payment Mix --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6">
                <div class="text-sm font-extrabold text-slate-900">
                    Payment Mix (Today)
                </div>
                <div class="text-xs text-slate-500 font-medium mt-1">
                    Based on today's bookings
                </div>

                <div class="mt-5 space-y-4">
                    @forelse ($mix as $m)
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="font-bold text-slate-900">
                                    {{ $m['name'] }}
                                    <span class="ml-2 text-xs text-slate-400 font-semibold">
                                        ({{ $m['count'] }} orders)
                                    </span>
                                </div>

                                <div class="text-slate-500 font-semibold">
                                    {{ $m['pct'] }}%
                                </div>
                            </div>

                            <div class="mt-2 h-2.5 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full {{ $m['class'] }} transition-all duration-500"
                                    style="width: {{ $m['pct'] }}%">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500 font-semibold">
                            No bookings today.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Alerts --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6">
                <div class="text-sm font-extrabold text-slate-900">Alerts</div>
                <div class="text-xs text-slate-500 font-medium mt-1">
                    Things that need attention
                </div>

                <div class="mt-4 space-y-3 text-sm">

                    {{-- Pending Orders --}}
                    @if ($pending > 0)
                        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                            class="block rounded-2xl bg-rose-50 border border-rose-100 p-4 hover:bg-rose-100 transition">

                            <div class="font-extrabold text-rose-800">
                                ⚠️ {{ $pending }} Pending bookings
                            </div>

                            <div class="text-rose-700 mt-1">
                                These orders are not assigned yet. Click to dispatch now.
                            </div>
                        </a>
                    @else
                        <div class="rounded-2xl bg-green-50 border border-green-100 p-4">
                            <div class="font-extrabold text-green-800">
                                ✅ No pending bookings
                            </div>
                            <div class="text-green-700 mt-1">
                                All orders have been assigned.
                            </div>
                        </div>
                    @endif


                    {{-- Pending Credit Payments --}}
                    @if ($pendingCredit > 0)
                        <a href="{{ route('admin.orders.index', ['payment_type' => 'credit']) }}"
                            class="block rounded-2xl bg-yellow-50 border border-yellow-100 p-4 hover:bg-yellow-100 transition">

                            <div class="font-extrabold text-yellow-800">
                                ⏳ {{ $pendingCredit }} Pending Credit payments
                            </div>

                            <div class="text-yellow-700 mt-1">
                                Check company billing or customer credit.
                            </div>
                        </a>
                    @else
                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                            <div class="font-extrabold text-slate-800">
                                ✔️ No pending Credit payments
                            </div>
                            <div class="text-slate-600 mt-1">
                                All credit payments are cleared.
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
