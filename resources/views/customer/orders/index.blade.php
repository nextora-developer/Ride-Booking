@extends('layouts.customer-app')

@section('title', 'My Orders')

@section('header')
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">My Orders</h1>
            <p class="text-slate-500 font-medium mt-1">Track your bookings and ride progress.</p>
        </div>

        <a href="{{ route('customer.book') }}"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white font-bold shadow-lg shadow-slate-200 hover:bg-slate-800 hover:-translate-y-0.5 transition-all">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Booking
        </a>
    </div>
@endsection

@section('content')
    @php
        $serviceMeta = fn($v) => match ($v) {
            'pickup_dropoff' => ['label' => '接送', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
            'charter' => ['label' => '包车', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'designated_driver' => [
                'label' => '代驾',
                'icon' =>
                    'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            ],
            'purchase' => ['label' => '代购', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
            'big_car' => [
                'label' => '大车',
                'icon' =>
                    'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            ],
            'driver_only' => [
                'label' => '司机',
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            ],
            default => ['label' => $v, 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        };

        $statusConfig = fn($v) => match ($v) {
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
            'assigned' => 'bg-blue-50 text-blue-700 border-blue-100',
            'on_the_way', 'arrived' => 'bg-amber-50 text-amber-700 border-amber-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };
    @endphp

    <div class="bg-white border border-gray-100 rounded-[2rem] overflow-hidden shadow-sm">

        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-slate-50/50">
            <h2 class="font-bold text-slate-900">Recent Bookings</h2>
            <span
                class="px-3 py-1 bg-white border border-gray-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest shadow-sm">
                {{ $orders->total() }} total
            </span>
        </div>

        @if ($orders->count() === 0)
            <div class="p-20 text-center">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">No trips found</h3>
                <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto">Your booking history will appear here once you've
                    made your first request.</p>
                <a href="{{ route('customer.book') }}"
                    class="mt-6 inline-flex items-center px-6 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                    Book My First Ride
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach ($orders as $o)
                    @php $meta = $serviceMeta($o->service_type); @endphp
                    <div class="group px-8 py-6 hover:bg-slate-50/50 transition-colors">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-6">

                            {{-- Icon & Status --}}
                            <div class="flex items-center gap-4 min-w-[140px]">
                                <div
                                    class="h-12 w-12 shrink-0 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-slate-900 shadow-sm group-hover:border-slate-200 transition-all">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-xs font-black text-slate-400 uppercase tracking-tighter leading-none mb-1">
                                        {{ $meta['label'] }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold border {{ $statusConfig($o->status) }} uppercase tracking-wider">
                                        {{ str_replace('_', ' ', $o->status) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Route --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex flex-col items-center gap-1 shrink-0">
                                            <div class="h-2 w-2 rounded-full bg-slate-300"></div>
                                            <div class="h-3 w-px bg-slate-200"></div>
                                            <div class="h-2 w-2 rounded-full bg-slate-900"></div>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-slate-400 truncate">{{ $o->pickup }}</div>
                                            <div class="text-sm font-black text-slate-900 truncate">{{ $o->dropoff }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Metadata --}}
                            <div
                                class="grid grid-cols-2 md:grid-cols-3 lg:flex items-center gap-8 text-left lg:text-right shrink-0">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date &
                                        Time</span>
                                    <span class="text-sm font-bold text-slate-700">
                                        {{ $o->schedule_type === 'scheduled' && $o->scheduled_at ? $o->scheduled_at->format('d M, h:i A') : $o->created_at->format('d M, h:i A') }}
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Shift</span>
                                    <span
                                        class="text-sm font-bold text-slate-700 capitalize">{{ $o->shift ?? 'Day' }}</span>
                                </div>

                                <div class="hidden md:flex flex-col">
                                    <span
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Payment</span>
                                    <span
                                        class="text-sm font-bold text-slate-700">{{ strtoupper($o->payment_type ?? 'Cash') }}</span>
                                </div>

                                <div class="col-span-2 md:col-span-1 lg:ml-4">
                                    <a href="{{ route('customer.orders.show', $o) }}"
                                        class="inline-flex items-center justify-center h-10 px-5 rounded-xl border border-gray-200 bg-white text-sm font-bold text-slate-600 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                                        View Details
                                    </a>
                                </div>
                            </div>

                        </div>

                        @if ($o->note)
                            <div
                                class="mt-4 flex items-start gap-2 px-4 py-3 rounded-xl bg-slate-50/50 border border-slate-100/50">
                                <svg class="h-4 w-4 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                <p class="text-xs text-slate-500 italic leading-snug">Note: {{ $o->note }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="px-8 py-6 bg-slate-50/30 border-t border-gray-50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
