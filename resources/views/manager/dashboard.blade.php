@extends('layouts.manager-app')

@section('title', 'Manager Dashboard')

@section('header')
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manager Dashboard</h1>
            <p class="text-slate-500 font-medium mt-1">派单控制台 · 快速查看订单与司机状态</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('manager.orders.index', ['status' => 'pending']) }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-slate-900 text-white font-bold shadow-lg shadow-slate-200 hover:bg-slate-800 hover:-translate-y-0.5 transition-all">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 6.75h12M8.25 12h12M8.25 17.25h12M3.75 6.75h.007v.008H3.75V6.75Zm0 5.25h.007v.008H3.75V12Zm0 5.25h.007v.008H3.75v-.008Z" />
                </svg>
                Pending Orders
            </a>
        </div>
    </div>
@endsection

@section('content')
    @php
        $shift = auth()->user()->shift ?? 'day';
        $shiftBadge = match (strtolower($shift)) {
            'night', '晚班' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
            'day', 'morning', '早班' => 'bg-amber-50 text-amber-700 border-amber-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Profile / Console --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Welcome card --}}
            <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 bg-slate-50/50 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest">Console</div>
                        <h2 class="text-xl font-extrabold text-slate-900 truncate mt-1">
                            Welcome, {{ explode(' ', auth()->user()->name)[0] }}
                        </h2>
                    </div>

                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black border {{ $shiftBadge }} uppercase tracking-widest">
                        {{ is_string($shift) ? $shift : 'Shift' }}
                    </span>
                </div>

                <div class="px-8 py-7">
                    <p class="text-slate-600 leading-relaxed">
                        🧭 你可以在这里快速查看待派单订单、司机状态，并进行派单操作。
                    </p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-gray-100 bg-white p-5">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</div>
                            <div class="mt-2 font-bold text-slate-900 break-all">{{ auth()->user()->email }}</div>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-white p-5">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Role</div>
                            <div class="mt-2 font-bold text-slate-900">Manager</div>
                            <div class="mt-1 text-xs text-slate-400 font-semibold">Shift-based dispatch</div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('manager.orders.index', ['status' => 'pending']) }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Go to Pending Orders
                        </a>

                        <a href="{{ route('manager.orders.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl border border-gray-200 bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 6.75h12M8.25 12h12M8.25 17.25h12M3.75 6.75h.007v.008H3.75V6.75Zm0 5.25h.007v.008H3.75V12Zm0 5.25h.007v.008H3.75v-.008Z" />
                            </svg>
                            View All Orders
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Tips --}}
            <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm p-7">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Quick Tips</h3>
                        <p class="text-sm text-slate-500 mt-1">派单时记得确认付款类型与班次</p>
                    </div>
                    <div
                        class="h-10 w-10 rounded-2xl bg-slate-50 border border-gray-100 flex items-center justify-center text-slate-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M12 14a4 4 0 10-4-4" />
                        </svg>
                    </div>
                </div>

                <ul class="mt-5 space-y-2 text-sm text-slate-600">
                    <li class="flex gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                        Pending 单优先派给同班次 driver（早班/晚班）。
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                        派单时要让司机知道：现金 / 挂单 / 转账。
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                        如果是 scheduled 订单，先看时间再派单。
                    </li>
                </ul>
            </div>
        </div>

        {{-- Right: Stats placeholder (以后接数据) --}}
        <div class="space-y-6">

            <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm p-7">
                <h3 class="text-lg font-extrabold text-slate-900">Today Snapshot</h3>
                <p class="text-sm text-slate-500 mt-1">（之后接数据库统计）</p>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-gray-100 bg-slate-50/50 p-4">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pending</div>
                        <div class="mt-1 text-2xl font-extrabold text-slate-900">—</div>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-slate-50/50 p-4">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Assigned</div>
                        <div class="mt-1 text-2xl font-extrabold text-slate-900">—</div>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-slate-50/50 p-4">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Completed</div>
                        <div class="mt-1 text-2xl font-extrabold text-slate-900">—</div>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-slate-50/50 p-4">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Drivers</div>
                        <div class="mt-1 text-2xl font-extrabold text-slate-900">—</div>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="{{ route('manager.orders.index') }}"
                        class="inline-flex w-full items-center justify-center h-11 rounded-2xl border border-gray-200 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                        Open Orders Board
                    </a>
                </div>
            </div>

            <div class="bg-slate-900 text-white rounded-[2rem] p-7">
                <div class="text-xs font-black uppercase tracking-widest opacity-70">Shift Mode</div>
                <div class="mt-2 text-xl font-extrabold">
                    {{ is_string($shift) ? ucfirst($shift) : 'Shift' }}
                </div>
                <p class="mt-2 text-sm opacity-80 leading-relaxed">
                    系统会帮你优先显示适合当前班次的订单与司机。
                </p>
            </div>

        </div>
    </div>
@endsection
