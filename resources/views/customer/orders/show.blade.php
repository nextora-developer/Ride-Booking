@extends('layouts.customer-app')

@section('title', 'Booking Details')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Booking #{{ $order->id }}
            </h1>
            <p class="text-slate-500 mt-1">
                Placed on {{ $order->created_at->format('d M Y, h:i A') }}
            </p>
        </div>

        <a href="{{ route('customer.orders.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
            Back
        </a>
    </div>
@endsection

@section('content')
    @php
        $serviceLabel = match ($order->service_type) {
            'pickup_dropoff' => '接送',
            'charter' => '包车',
            'designated_driver' => '代驾',
            'purchase' => '代购',
            'big_car' => '大车',
            'driver_only' => '司机',
            default => $order->service_type,
        };

        $statusBadge = match ($order->status) {
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
            'assigned' => 'bg-blue-50 text-blue-700 border-blue-100',
            'on_the_way', 'arrived' => 'bg-amber-50 text-amber-700 border-amber-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };

        $when =
            $order->schedule_type === 'scheduled' && $order->scheduled_at ? $order->scheduled_at : $order->created_at;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Trip Details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Trip Details</h2>
                        <p class="text-sm text-slate-500 mt-1">Booking info & route.</p>
                    </div>

                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusBadge }} uppercase tracking-wider">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="text-slate-400">Service Type</p>
                        <p class="font-semibold text-slate-900">{{ $serviceLabel }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400">Payment Type</p>
                        <p class="font-semibold text-slate-900">
                            {{ strtoupper($order->payment_type ?? 'cash') }}
                            @if ($order->payment_status)
                                <span class="text-slate-400 font-bold text-xs">•
                                    {{ strtoupper($order->payment_status) }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-slate-400">Pickup</p>
                        <p class="font-semibold text-slate-900">{{ $order->pickup }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-slate-400">Dropoff</p>
                        <p class="font-semibold text-slate-900">{{ $order->dropoff }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400">Date & Time</p>
                        <p class="font-semibold text-slate-900">{{ $when->format('d M Y, h:i A') }}</p>
                        @if ($order->schedule_type === 'scheduled')
                            <p class="text-xs text-slate-400 mt-1">Scheduled</p>
                        @else
                            <p class="text-xs text-slate-400 mt-1">Instant</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-slate-400">Shift</p>
                        <p class="font-semibold text-slate-900 capitalize">{{ $order->shift ?? 'day' }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400">Driver Assigned</p>
                        <p class="font-semibold text-slate-900">
                            {{ $order->driver?->name ?? 'Not Assigned Yet' }}
                        </p>
                        @if ($order->assigned_at)
                            <p class="text-xs text-slate-400 mt-1">Assigned at
                                {{ $order->assigned_at->format('d M, h:i A') }}</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-slate-400">Manager</p>
                        <p class="font-semibold text-slate-900">
                            {{ $order->manager?->name ?? '-' }}
                        </p>
                    </div>

                    @if ($order->note)
                        <div class="sm:col-span-2">
                            <p class="text-slate-400">Note</p>
                            <p class="font-semibold text-slate-900">{{ $order->note }}</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-6">

            {{-- Driver Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Driver</h2>

                @if ($order->driver)
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="font-bold text-slate-900 truncate">{{ $order->driver->name }}</p>
                            <p class="text-sm text-slate-500">Shift: <span
                                    class="font-semibold capitalize">{{ $order->driver->shift ?? '-' }}</span></p>
                        </div>

                        @if (!empty($order->driver->phone))
                            <a href="tel:{{ $order->driver->phone }}"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
                                Call
                            </a>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-slate-500">No driver assigned yet.</p>
                @endif
            </div>

            {{-- Payment (暂时先不显示金额，因为你 Order model 目前没有 base_fare/total 字段) --}}
            <div class="bg-slate-50 border border-gray-100 rounded-2xl p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-2">Payment</h2>
                <p class="text-sm text-slate-600">
                    Method: <span class="font-bold">{{ strtoupper($order->payment_type ?? 'cash') }}</span>
                    @if ($order->payment_status)
                        • Status: <span class="font-bold">{{ strtoupper($order->payment_status) }}</span>
                    @endif
                </p>
                <p class="text-xs text-slate-400 mt-2">
                    (Amount section can be added after you include fare/total columns.)
                </p>
            </div>

        </div>
    </div>
@endsection
