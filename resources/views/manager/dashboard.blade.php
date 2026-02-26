@extends('layouts.manager-app')

@section('title', '派单控制台')

@section('header')
    {{-- App Style Top Bar --}}
    <div class="px-4 py-4 flex items-center justify-between">
        <div>
            <span
                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none">Management</span>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">控制台</h1>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="h-10 w-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-500 shadow-sm active:scale-95 transition-all">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <div
                class="h-10 w-10 rounded-2xl bg-slate-800 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-slate-200">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </div>
@endsection

@section('content')
    @php
        use App\Models\Order;
        use App\Models\User;

        $shift = auth()->user()->shift ?? 'day';
        $isNight = in_array(strtolower((string) $shift), ['night', '晚班']);
        $shiftValue = $isNight ? 'night' : 'day';

        $shiftLabel = $isNight ? '晚班 Dispatch' : '早班 Dispatch';
        $shiftColor = $isNight ? 'text-indigo-600 bg-indigo-50' : 'text-amber-600 bg-amber-50';

        // ✅ 只看当前班次订单（前提：orders 有 shift 字段）
        $base = Order::query()->where('shift', $shiftValue);

        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        // 核心统计
        $pendingCount = (clone $base)->where('status', 'pending')->count();

        $assignedCount = (clone $base)->whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip'])->count();

        $completedToday = (clone $base)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$todayStart, $todayEnd])
            ->count();

        // 今日付款类型（用 created_at 更合理：今天产生的单）
        $cashToday = (clone $base)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('payment_type', 'cash')
            ->count();
        $creditToday = (clone $base)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('payment_type', 'credit')
            ->count();
        $transferToday = (clone $base)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('payment_type', 'transfer')
            ->count();

        // ✅ 在线司机（推荐：用 last_active_at 判定“还活着”）
        $onlineDrivers = User::query()
            ->where('role', 'driver')
            ->where('shift', $shiftValue)
            ->where('driver_status', 'approved') // ✅ 你的字段是 driver_status
            ->where('is_online', 1)
            ->where('last_active_at', '>=', now()->subMinutes(5)) // 5分钟内有心跳才算在线
            ->count();

        // ✅ 全部司机（本班次）
        $totalDrivers = User::query()
            ->where('role', 'driver')
            ->where('shift', $shiftValue)
            ->where('driver_status', 'approved')
            ->count();

        // ✅ 挂单顾客（你说的“挂单” = payment_type credit 且未完成）
        $creditCustomers = (clone $base)
            ->where('payment_type', 'credit')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->distinct('user_id')
            ->count('user_id');
    @endphp

    <div class="space-y-6 pb-7 px-1">

        {{-- 1. Profile App Card --}}
        <div
            class="relative overflow-hidden bg-white rounded-[2.5rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-50">
            {{-- Background Decoration --}}
            <div class="absolute -right-6 -top-6 h-32 w-32 bg-slate-50 rounded-full opacity-50"></div>

            <div class="relative z-10 flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-[1.25rem] bg-slate-100 flex items-center justify-center text-2xl">👋
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-800 leading-tight">
                                你好，{{ explode(' ', auth()->user()->name)[0] }}</h2>
                            <p class="text-xs font-bold text-slate-400 mt-0.5">今天有新的任务等待处理</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1.5 rounded-xl text-[10px] font-black border border-current {{ $shiftColor }} uppercase tracking-widest">
                        {{ $shiftLabel }}
                    </span>
                </div>

                {{-- Quick Info Tabs --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100/50">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">角色权限</span>
                        <div class="text-sm font-bold text-slate-700 mt-1 flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Manager
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100/50">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">账号状态</span>
                        <div class="text-sm font-bold text-slate-700 mt-1">在线</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Stats Grid (iOS Widget Style) --}}
        <div>
            <div class="flex items-center justify-between px-2 mb-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">今日看板</h3>
                <span class="text-[10px] font-bold text-indigo-500">实时更新</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                {{-- Stat Item --}}
                <div
                    class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-50 relative group active:scale-95 transition-all">
                    <div class="h-8 w-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center mb-3">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-black text-slate-400 uppercase">待派单</div>
                    <div class="text-2xl font-black text-slate-800 mt-0.5">{{ $pendingCount }}</div>
                    <div class="absolute top-5 right-5 h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></div>
                </div>

                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-50 active:scale-95 transition-all">
                    <div class="h-8 w-8 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center mb-3">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-black text-slate-400 uppercase">已分派</div>
                    <div class="text-2xl font-black text-slate-800 mt-0.5">{{ $assignedCount }}</div>
                </div>

                {{-- 在线司机 --}}
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-50 active:scale-95 transition-all">
                    <div class="h-8 w-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2.25c-4.142 0-7.5 2.239-7.5 5v.75h15v-.75c0-2.761-3.358-5-7.5-5Z" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-black text-slate-400 uppercase">在线司机</div>
                    <div class="text-2xl font-black text-slate-800 mt-0.5">
                        {{ $onlineDrivers }}<span class="text-sm font-black text-slate-400"> / {{ $totalDrivers }}</span>
                    </div>
                </div>

                {{-- 今日完成 --}}
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-50 active:scale-95 transition-all">
                    <div class="h-8 w-8 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center mb-3">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div class="text-[10px] font-black text-slate-400 uppercase">今日完成</div>
                    <div class="text-2xl font-black text-slate-800 mt-0.5">{{ $completedToday }}</div>
                </div>
            </div>
        </div>

        {{-- 3. Action Buttons --}}
        <div class="flex flex-col gap-3">
            <a href="{{ route('manager.orders.index', ['status' => 'pending']) }}"
                class="w-full flex items-center justify-between p-5 bg-slate-800 rounded-[1.75rem] text-white shadow-xl shadow-slate-200 active:scale-[0.98] transition-all">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="font-black tracking-tight">立即处理待派订单</span>
                </div>
                <svg class="h-5 w-5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="{{ route('manager.orders.index') }}"
                class="w-full flex items-center justify-between p-5 bg-white border border-slate-100 rounded-[1.75rem] text-slate-700 active:scale-[0.98] transition-all">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </div>
                    <span class="font-black tracking-tight text-slate-600">查看全部订单列表</span>
                </div>
                <svg class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="3">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        {{-- 4. App Notice Card (Bottom) --}}
        <div class="bg-indigo-600 rounded-[2.5rem] p-7 text-white relative overflow-hidden">
            <svg class="absolute -bottom-4 -right-4 h-24 w-24 text-white/10" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
            </svg>
            <div class="relative z-10">
                <h4 class="font-black text-lg leading-tight">调度提醒</h4>
                <p class="text-white/70 text-xs font-bold mt-2 leading-relaxed">
                    当前处于 <span class="text-white underline">{{ $shiftLabel }}</span> 时间段。请确保所有现金单已与司机核对金额。
                </p>
            </div>
        </div>

    </div>
@endsection
