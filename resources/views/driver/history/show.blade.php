@extends('layouts.driver-app')

@section('title', '订单详情')

@section('header')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Order #{{ $order->id }}
            </h1>
            <p class="text-slate-500 mt-1">查看历史订单详情</p>
        </div>

        <a href="{{ route('driver.history.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
            Back
        </a>
    </div>
@endsection

@section('content')
    @php
        $badge = match ($order->status) {
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest">Route</div>

                        <div class="mt-3">
                            <div class="text-sm font-bold text-slate-500">Pickup</div>
                            <div class="text-lg font-extrabold text-slate-900">{{ $order->pickup }}</div>
                        </div>

                        <div class="mt-4">
                            <div class="text-sm font-bold text-slate-500">Dropoff</div>
                            <div class="text-lg font-extrabold text-slate-900">{{ $order->dropoff }}</div>
                        </div>
                    </div>

                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black border {{ $badge }} uppercase tracking-widest">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Time</div>
                        <div class="mt-1 font-bold text-slate-900">{{ $order->created_at?->format('d M Y, h:i A') }}</div>
                    </div>

                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Payment</div>
                        <div class="mt-1 font-bold text-slate-900">{{ strtoupper($order->payment_type ?? '-') }}</div>
                    </div>

                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Schedule</div>
                        <div class="mt-1 font-bold text-slate-900">
                            {{ $order->schedule_type === 'scheduled' ? 'Scheduled' : 'Instant' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Shift</div>
                        <div class="mt-1 font-bold text-slate-900 capitalize">{{ $order->shift ?? '-' }}</div>
                    </div>

                    @if ($order->note)
                        <div class="sm:col-span-2">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Note</div>
                            <div class="mt-1 font-bold text-slate-900">{{ $order->note }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-7 shadow-sm">
                <div class="text-sm font-extrabold text-slate-900">Quick Info</div>
                <div class="mt-4 space-y-2 text-sm text-slate-600 font-semibold">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Order ID</span>
                        <span class="font-extrabold text-slate-900">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Status</span>
                        <span class="font-extrabold text-slate-900">{{ str_replace('_', ' ', $order->status) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Payment</span>
                        <span class="font-extrabold text-slate-900">{{ strtoupper($order->payment_type ?? '-') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
