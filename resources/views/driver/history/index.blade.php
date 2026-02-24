@extends('layouts.driver-app')

@section('title', '历史记录')

@section('header')
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">历史记录</h1>
            <p class="text-slate-500 mt-1">查看你已完成 / 取消的订单</p>
        </div>

        <div class="hidden sm:flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-400">
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                Completed: {{ (int) ($counts->completed ?? 0) }}
            </span>
            <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-100">
                Cancelled: {{ (int) ($counts->cancelled ?? 0) }}
            </span>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">

        {{-- Filters --}}
        <form method="GET" action="{{ route('driver.history.index') }}"
            class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Search</label>
                    <input name="q" value="{{ $q }}" placeholder="Pickup / Dropoff / Order ID"
                        class="mt-1 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700
                               focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</label>
                    <select name="status"
                        class="mt-1 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700
                               focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                        <option value="">All</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        class="w-full h-11 rounded-2xl bg-indigo-600 text-white font-extrabold hover:bg-indigo-700 transition">
                        Apply
                    </button>
                    <a href="{{ route('driver.history.index') }}"
                        class="h-11 px-4 inline-flex items-center justify-center rounded-2xl border border-gray-200 bg-white text-slate-700 font-extrabold hover:bg-slate-50 transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- List --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <div class="text-sm font-extrabold text-slate-900">我的订单</div>
                <div class="text-xs text-slate-400 font-semibold mt-1">
                    Total: {{ (int) ($counts->total ?? 0) }}
                </div>
            </div>

            @if ($orders->count())
                <div class="divide-y divide-gray-100">
                    @foreach ($orders as $o)
                        @php
                            $badge = match ($o->status) {
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                                default => 'bg-slate-50 text-slate-600 border-slate-100',
                            };
                        @endphp

                        <a href="{{ route('driver.history.show', $o) }}" class="block p-5 hover:bg-slate-50 transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <div class="text-sm font-extrabold text-slate-900">
                                            Order #{{ $o->id }}
                                        </div>
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[11px] font-black border {{ $badge }} uppercase tracking-widest">
                                            {{ str_replace('_', ' ', $o->status) }}
                                        </span>
                                    </div>

                                    <div class="mt-3 space-y-2">
                                        <div>
                                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                Pickup</div>
                                            <div class="text-sm font-bold text-slate-900 truncate">{{ $o->pickup }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                Dropoff</div>
                                            <div class="text-sm font-bold text-slate-900 truncate">{{ $o->dropoff }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 text-xs text-slate-400 font-semibold">
                                        {{ $o->created_at?->format('d M Y, h:i A') }} •
                                        {{ strtoupper($o->payment_type ?? '-') }}
                                    </div>
                                </div>

                                <div class="shrink-0 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5.25 15 12l-6 6.75" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="p-5">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <div class="text-sm font-black text-slate-400 uppercase tracking-widest">No Records</div>
                    <div class="text-xl font-extrabold text-slate-900 mt-3">还没有历史订单</div>
                    <div class="text-sm text-slate-500 mt-2">完成订单后会出现在这里</div>
                </div>
            @endif
        </div>

    </div>
@endsection
