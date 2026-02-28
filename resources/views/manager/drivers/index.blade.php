@extends('layouts.manager-app')

@section('title', '司机管理')

@section('header')
    <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">
        <div class="absolute left-0 top-1/2 -translate-y-1/2">
            <a href="{{ route('manager.dashboard') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>

        <div class="text-center">
            <h1 class="text-lg font-black text-slate-800 leading-none">司机管理</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Driver Control</p>
        </div>
    </div>
@endsection

@section('content')

    {{-- Stats --}}
    <div class="px-2 mt-4 grid grid-cols-3 gap-3 mb-8">
        <div
            class="bg-white p-4 rounded-3xl border border-slate-200 shadow-[0_12px_26px_rgba(15,23,42,0.08)] relative overflow-hidden group">
            <div class="text-[9px] text-slate-500 font-black uppercase tracking-tighter mb-1">总人数</div>
            <div class="text-2xl font-black text-slate-900 leading-none">{{ $total }}</div>
            <div
                class="absolute -right-2 -bottom-2 opacity-[0.06] group-hover:scale-110 transition-transform text-4xl">
                👥
            </div>
        </div>

        <div
            class="bg-emerald-100/50 p-4 rounded-3xl border border-emerald-200/60 shadow-[0_12px_26px_rgba(15,23,42,0.08)] relative overflow-hidden group">
            <div class="text-[9px] text-emerald-700/70 font-black uppercase tracking-tighter mb-1">空闲中</div>
            <div class="text-2xl font-black text-emerald-700 leading-none">{{ $available }}</div>
            <div
                class="absolute -right-2 -bottom-2 opacity-[0.10] group-hover:scale-110 transition-transform text-4xl">
                ✅
            </div>
        </div>

        <div
            class="bg-amber-100/50 p-4 rounded-3xl border border-amber-200/60 shadow-[0_12px_26px_rgba(15,23,42,0.08)] relative overflow-hidden group">
            <div class="text-[9px] text-amber-700/70 font-black uppercase tracking-tighter mb-1">工作中</div>
            <div class="text-2xl font-black text-amber-700 leading-none">{{ $onJob }}</div>
            <div
                class="absolute -right-2 -bottom-2 opacity-[0.10] group-hover:scale-110 transition-transform text-4xl">
                🚗
            </div>
        </div>
    </div>

    {{-- Driver List --}}
    <div class="px-2 space-y-3 pb-24">
        <div class="flex items-center justify-between px-2 mb-2">
            <h2 class="text-sm font-black text-slate-500 uppercase tracking-widest">司机列表</h2>
            <div class="h-1 flex-1 mx-4 bg-slate-50 rounded-full"></div>
        </div>

        @forelse ($drivers as $driver)
            @php
                $order = $driver->current_order;
                $online = (bool) ($driver->is_online ?? false);

                $statusConfig = match (true) {
                    $order !== null => [
                        'color' => 'amber',
                        'label' => '进行中 #' . $order->id,
                        'pulse' => 'bg-amber-500',
                        'bg' => 'bg-amber-100/35',
                        'border' => 'border-amber-200/60',
                    ],
                    $online => [
                        'color' => 'emerald',
                        'label' => '空闲中',
                        'pulse' => 'bg-emerald-600',
                        'bg' => 'bg-white',
                        'border' => 'border-slate-200',
                    ],
                    default => [
                        'color' => 'slate',
                        'label' => '离线',
                        'pulse' => 'bg-slate-400',
                        'bg' => 'bg-slate-100/60',
                        'border' => 'border-slate-200',
                    ],
                };
            @endphp

            <div
                class="{{ $statusConfig['bg'] }} rounded-[2rem] p-5 border {{ $statusConfig['border'] }}
                       shadow-[0_12px_26px_rgba(15,23,42,0.08)]
                       flex items-center justify-between group active:scale-[0.98] transition-all">
                <div class="flex items-center gap-4 min-w-0">

                    {{-- Avatar --}}
                    <div class="relative shrink-0">
                        <div
                            class="h-14 w-14 rounded-[1.2rem] bg-slate-900 text-white flex items-center justify-center text-lg font-black
                                   shadow-[0_16px_36px_rgba(15,23,42,0.20)]
                                   group-hover:rotate-2 transition-transform">
                            {{ strtoupper(substr($driver->full_name, 0, 1)) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full bg-white p-1 shadow-sm">
                            <div
                                class="h-full w-full rounded-full {{ $statusConfig['pulse'] }} {{ $online ? 'animate-pulse' : '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-slate-900 truncate">{{ $driver->full_name }}</h3>
                            <span
                                class="text-[10px] font-black px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 uppercase leading-none border border-slate-200">
                                {{ $driver->shift ?? '?' }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="text-[10px] font-black text-{{ $statusConfig['color'] }}-700 uppercase tracking-tight">
                                {{ $statusConfig['label'] }}
                            </span>

                            @if ($driver->car_plate)
                                <span class="text-[10px] text-slate-400">•</span>
                                <span class="text-[10px] font-black text-slate-600 tracking-widest">
                                    {{ $driver->car_plate }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <a href="{{ route('manager.drivers.edit', $driver) }}"
                    class="h-11 w-11 rounded-2xl bg-white border border-slate-200
                           shadow-[0_10px_22px_rgba(15,23,42,0.08)]
                           flex items-center justify-center text-slate-500
                           hover:text-slate-900 hover:border-slate-900 transition-all">

                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                </a>
            </div>
        @empty
            <div class="py-20 text-center">
                <div
                    class="h-20 w-20 bg-slate-100 border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-[0_10px_22px_rgba(15,23,42,0.06)]">
                    <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="font-black text-slate-900 uppercase tracking-widest text-sm">暂无司机数据</h3>
                <p class="text-xs text-slate-600 mt-2 font-bold">“目前还没有司机记录”</p>
            </div>
        @endforelse

        {{ $drivers->links() }}
    </div>

@endsection
