@extends('layouts.customer-app')

@section('title', 'Customer Dashboard')

@section('header')
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Welcome back, {{ explode(' ', auth()->user()->name)[0] }}!
            </h1>
            <p class="text-slate-500 font-medium mt-1">
                Your next journey is just a few clicks away.
            </p>
        </div>

        <a href="{{ route('customer.book') }}"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white font-bold shadow-lg shadow-slate-200 hover:bg-slate-800 hover:-translate-y-0.5 transition-all">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Book a Ride
        </a>
    </div>
@endsection

@section('content')

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">
        <div class="bg-white border border-gray-100 rounded-3xl p-6 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Trips</span>
                <div class="p-2 bg-slate-50 rounded-lg text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A2 2 0 013 15.382V6m18 14l-5.447-2.724a2 2 0 01-1.053-1.788V6m-7 14V6m0 0l5.447-2.724A2 2 0 0115 4.618V13m-7-7l-5.447 2.724A2 2 0 002 6.618V15">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-black mt-3 text-slate-900">{{ $totalTrips ?? 0 }}</div>
        </div>

        <div class="bg-white border border-gray-100 rounded-3xl p-6 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">In Progress</span>
                <div class="p-2 bg-amber-50 rounded-lg text-amber-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-black mt-3 text-amber-600">{{ $inProgress ?? 0 }}</div>
        </div>

        <div class="bg-white border border-gray-100 rounded-3xl p-6 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Completed</span>
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-black mt-3 text-emerald-600">{{ $completed ?? 0 }}</div>
        </div>
    </div>

    {{-- Services Grid --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6 px-1">
            <h2 class="text-lg font-bold text-slate-900">Our Services</h2>
            <a href="#" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">View all
                details</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $services = [
                    [
                        'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
                        'name' => 'Point to Point',
                        'desc' => 'Fastest way to your destination',
                    ],
                    [
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'name' => 'Hourly Charter',
                        'desc' => 'Vehicle at your service for hours',
                    ],
                    [
                        'icon' =>
                            'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                        'name' => 'Designated Driver',
                        'desc' => 'We drive your car safely',
                    ],
                    [
                        'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                        'name' => 'Concierge/Purchase',
                        'desc' => 'We shop and deliver to you',
                    ],
                    [
                        'icon' =>
                            'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                        'name' => 'Group Van',
                        'desc' => 'Spacious travel for up to 10',
                    ],
                    [
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'name' => 'Personal Chauffeur',
                        'desc' => 'Professional drivers for hire',
                    ],
                ];
            @endphp

            @foreach ($services as $service)
                <div
                    class="group bg-white border border-gray-100 rounded-2xl p-5 hover:border-slate-900/10 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $service['icon'] }}" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $service['name'] }}</h3>
                            <p class="text-xs font-medium text-slate-400">{{ $service['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Recent Bookings</h2>

            <a href="{{ route('customer.orders.index') }}"
                class="text-xs font-bold text-slate-400 uppercase tracking-tighter hover:text-slate-900 transition-colors">
                View All
            </a>
        </div>

        @if (($recent ?? collect())->count() === 0)
            <div class="p-12 flex flex-col items-center justify-center text-center">
                <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900">No bookings yet</h3>
                <p class="text-sm text-slate-400 mt-1 max-w-xs">When you book your first ride, it will appear here for you
                    to track.</p>
                <a href="{{ route('customer.book') }}"
                    class="mt-6 text-sm font-bold text-slate-900 underline decoration-slate-200 underline-offset-4 hover:decoration-slate-900 transition-all">
                    Start your first booking
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach ($recent as $o)
                    <div class="px-6 py-4 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="font-bold text-slate-900 truncate">
                                {{ $o->pickup }} → {{ $o->dropoff }}
                            </div>
                            <div class="text-xs text-slate-400 mt-1">
                                Service: <span class="font-semibold text-slate-600">{{ $o->service_type }}</span>
                                • Shift: <span class="font-semibold text-slate-600">{{ $o->shift }}</span>
                                • {{ $o->created_at?->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        @php
                            $badge = match ($o->status) {
                                'completed' => 'bg-emerald-50 text-emerald-700',
                                'cancelled' => 'bg-red-50 text-red-700',
                                'assigned' => 'bg-blue-50 text-blue-700',
                                'on_the_way', 'arrived' => 'bg-amber-50 text-amber-700',
                                default => 'bg-slate-50 text-slate-700',
                            };
                        @endphp

                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                {{ strtoupper(str_replace('_', ' ', $o->status)) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
