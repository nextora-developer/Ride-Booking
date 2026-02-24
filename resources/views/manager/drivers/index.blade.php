@extends('layouts.manager-app')

@section('title', 'Drivers')

@section('header')
    <h1 class="text-3xl font-extrabold text-slate-900">Drivers</h1>
@endsection

@section('content')

    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow">
            <div class="text-xs text-slate-400 font-bold">Total Drivers</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $total }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow">
            <div class="text-xs text-slate-400 font-bold">Available</div>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $available }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow">
            <div class="text-xs text-slate-400 font-bold">On Job</div>
            <div class="text-2xl font-extrabold text-amber-600 mt-1">{{ $onJob }}</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow divide-y">
        @foreach ($drivers as $driver)
            @php
                $order = $driver->current_order;
            @endphp

            <div class="p-6 flex justify-between items-center">
                <div>
                    <div class="font-bold text-slate-900">{{ $driver->name }}</div>
                    <div class="text-xs text-slate-400 capitalize">
                        {{ $driver->shift ?? '-' }} shift
                    </div>
                </div>

                <div class="text-sm font-bold">
                    @if ($order)
                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700">
                            {{ strtoupper($order->status) }}
                        </span>
                        <span class="text-xs text-slate-400 ml-2">
                            #{{ $order->id }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">
                            AVAILABLE
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

@endsection
