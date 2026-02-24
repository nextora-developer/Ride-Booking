@extends('layouts.customer-app')

@section('title', 'Create Booking')

@section('header')
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create Booking</h1>
            <p class="text-slate-500 font-medium mt-1">Select a service and provide your trip details below.</p>
        </div>

        <a href="{{ route('customer.home') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-slate-600 text-sm font-bold hover:bg-slate-50 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back
        </a>
    </div>
@endsection

@section('content')

@php
    $services = [
        ['key' => 'pickup_dropoff',    'label' => '接送', 'desc' => 'Point-to-point', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
        ['key' => 'charter',           'label' => '包车', 'desc' => 'Hourly charter', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['key' => 'designated_driver',  'label' => '代驾', 'desc' => 'Your car, our driver', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
        ['key' => 'purchase',          'label' => '代购', 'desc' => 'Buy & deliver', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
        ['key' => 'big_car',           'label' => '大车', 'desc' => 'MPV / Van', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['key' => 'driver_only',       'label' => '司机', 'desc' => 'Hire a driver', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

    {{-- Left: Form --}}
    <div class="lg:col-span-2 space-y-6">
        <form method="POST" action="{{ route('customer.book.store') }}" class="bg-white border border-gray-100 rounded-[2rem] p-8 shadow-sm">
            @csrf

            {{-- Service Type --}}
            <div class="mb-10">
                <label class="block text-sm font-bold text-slate-900 mb-4 uppercase tracking-widest text-center md:text-left">
                    Select Service
                </label>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($services as $s)
                        <label class="group relative cursor-pointer">
                            <input type="radio" name="service_type" value="{{ $s['key'] }}" class="peer sr-only" required
                                   @checked(old('service_type') === $s['key'])>
                            
                            <div class="h-full flex flex-col items-center text-center p-5 rounded-2xl border border-gray-100 bg-white transition-all duration-200 
                                        peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white 
                                        group-hover:border-slate-200 group-hover:shadow-md">
                                <svg class="h-6 w-6 mb-3 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}" />
                                </svg>
                                <span class="text-sm font-bold block">{{ $s['label'] }}</span>
                                <span class="text-[10px] opacity-60 font-medium leading-tight mt-1">{{ $s['desc'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Location Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pickup Point</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <input type="text" name="pickup" value="{{ old('pickup') }}" required placeholder="Where are we starting?"
                               class="w-full bg-slate-50 border-none rounded-2xl pl-11 pr-4 py-3.5 text-sm font-medium focus:ring-2 focus:ring-slate-900 transition-all placeholder:text-slate-300">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Destination</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                        </span>
                        <input type="text" name="dropoff" value="{{ old('dropoff') }}" required placeholder="Where to?"
                               class="w-full bg-slate-50 border-none rounded-2xl pl-11 pr-4 py-3.5 text-sm font-medium focus:ring-2 focus:ring-slate-900 transition-all placeholder:text-slate-300">
                    </div>
                </div>
            </div>

            {{-- Time & Schedule --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Arrival Type</label>
                    <select name="schedule_type" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3.5 text-sm font-bold focus:ring-2 focus:ring-slate-900 appearance-none transition-all">
                        <option value="now" @selected(old('schedule_type','now')==='now')>Book for Now</option>
                        <option value="scheduled" @selected(old('schedule_type')==='scheduled')>Schedule for Later</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Schedule At</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                           class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3.5 text-sm font-medium focus:ring-2 focus:ring-slate-900 transition-all">
                </div>
            </div>

            {{-- Notes --}}
            <div class="space-y-2 mb-10">
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Additional Notes</label>
                <textarea name="note" rows="3" placeholder="Luggage count, child seats, or specific requests..."
                          class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3.5 text-sm font-medium focus:ring-2 focus:ring-slate-900 transition-all placeholder:text-slate-300">{{ old('note') }}</textarea>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-slate-900 text-white font-bold text-lg shadow-xl shadow-slate-200 hover:bg-slate-800 hover:-translate-y-1 active:translate-y-0 transition-all">
                Confirm and Send Booking
                <svg class="h-5 w-5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>

    {{-- Right: Sidebar Info --}}
    <div class="lg:sticky lg:top-24 space-y-6">
        <div class="bg-white border border-gray-100 rounded-3xl p-6">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Account Summary</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between py-2">
                    <span class="text-xs font-bold text-slate-400 uppercase">Passenger</span>
                    <span class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t border-slate-50">
                    <span class="text-xs font-bold text-slate-400 uppercase">Verification</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-emerald-50 text-emerald-600 uppercase">Verified</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t border-slate-50">
                    <span class="text-xs font-bold text-slate-400 uppercase">Status</span>
                    <span class="text-sm font-bold text-amber-500 italic">Ready to submit</span>
                </div>
            </div>

            <div class="mt-8 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                    <span class="text-[11px] font-bold text-slate-900 uppercase">Quick Tips</span>
                </div>
                <ul class="text-[12px] text-slate-500 font-medium space-y-2 leading-tight">
                    <li>• Specify exact gate numbers for airport pickups.</li>
                    <li>• For <b>Concierge</b>, list items clearly in the notes.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection