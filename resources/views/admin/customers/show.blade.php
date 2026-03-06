@extends('layouts.admin-app')

@section('title', '顾客详情')

@section('header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        {{-- LEFT : 顾客信息 --}}
        <div class="min-w-0">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 truncate">
                {{ $customer->name }}
            </h1>

            <div class="mt-2 text-sm text-slate-500 font-semibold">
                顾客ID：{{ $customer->id }}
                <span class="mx-2">•</span>
                加入时间：{{ optional($customer->created_at)->format('d M Y, h:i A') }}
            </div>
        </div>

        {{-- RIGHT : 返回按钮 --}}
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.customers.index') }}"
                class="h-10 px-4 inline-flex items-center gap-2
                       rounded-2xl bg-white border border-gray-200
                       text-sm font-extrabold
                       hover:bg-gray-50 transition">

                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                </svg>

                返回
            </a>
        </div>

    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">

        {{-- LEFT COLUMN: 业务往来记录 --}}
        <div class="xl:col-span-2 space-y-8">

            {{-- 1. 快速联系卡片 --}}
            <div
                class="rounded-[2.5rem] bg-white border border-slate-100 shadow-sm p-2 flex flex-wrap sm:flex-nowrap gap-2">
                <div
                    class="flex-1 rounded-[2rem] bg-slate-50 p-6 flex items-center gap-4 group hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-slate-100">
                    <div class="h-12 w-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-xl">📞</div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">主要电话</p>
                        <p class="text-sm font-black text-slate-900 leading-none mt-1">{{ $customer->phone ?? '—' }}</p>
                    </div>
                </div>

                <div
                    class="flex-1 rounded-[2rem] bg-slate-50 p-6 flex items-center gap-4 group hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-slate-100">
                    <div class="h-12 w-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-xl">✉️</div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">邮箱地址</p>
                        <p class="text-sm font-black text-slate-900 leading-none mt-1 truncate">
                            {{ $customer->email ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- 2. 最近订单 --}}
            <div class="rounded-[2.5rem] bg-white border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">订单记录</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">最近 10 次记录</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <div class="divide-y divide-slate-50">
                    @forelse($orders as $o)
                        @php
                            $routePoints = [];

                            if (!empty($o->pickup)) {
                                $routePoints[] = $o->pickup;
                            }

                            if (!empty($o->dropoffs) && is_array($o->dropoffs)) {
                                $routePoints = array_merge($routePoints, array_values(array_filter($o->dropoffs)));
                            } elseif (!empty($o->dropoff)) {
                                $routePoints[] = $o->dropoff;
                            }

                            $displayPoints = array_slice($routePoints, 0, 3);
                            $routeText = !empty($displayPoints) ? implode(' → ', $displayPoints) : '-';

                            if (count($routePoints) > 3) {
                                $routeText .= ' → ...';
                            }
                        @endphp

                        <a href="{{ route('admin.orders.show', $o) }}"
                            class="group block px-8 py-6 hover:bg-slate-50/80 transition-all">
                            <div class="flex items-center justify-between gap-6">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors">
                                            #{{ str_pad((string) $o->id, 6, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        <span
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $o->created_at->format('d M, Y') }}</span>
                                    </div>

                                    <div class="mt-2 text-xs font-bold text-slate-600 truncate">
                                        {{ $routeText }}
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    @php
                                        $status = strtolower(trim((string) ($o->status ?? '')));

                                        $statusClasses = match ($status) {
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'assigned',
                                            'on_the_way',
                                            'arrived',
                                            'in_trip'
                                                => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };

                                        $statusLabel = match ($status) {
                                            'pending' => '待处理',
                                            'assigned' => '已指派',
                                            'on_the_way' => '前往途中',
                                            'arrived' => '已到达',
                                            'in_trip' => '进行中',
                                            'completed' => '已完成',
                                            'cancelled' => '已取消',
                                            default => '未知',
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex px-3 py-1 rounded-xl text-[10px] font-black tracking-tighter border shadow-sm transition-all
                        {{ $statusClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="py-20 text-center">
                            <div class="text-4xl mb-4 text-slate-200">📦</div>
                            <p class="text-sm font-black text-slate-400 uppercase tracking-widest">暂无订单记录</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: 客户核心档案 --}}
        <div class="space-y-6">
            {{-- Profile Card --}}
            <div class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-2xl shadow-slate-200 relative overflow-hidden">
                {{-- 背景装饰 --}}
                <div class="absolute -right-6 -bottom-6 h-32 w-32 bg-indigo-500/20 rounded-full blur-2xl"></div>

                <div class="relative">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-16 w-16 rounded-[1.5rem] bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-2xl font-black shadow-lg">
                            {{ strtoupper(mb_substr($customer->name ?? 'C', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-xl font-black truncate tracking-tight">{{ $customer->name }}</h2>
                            <p class="text-xs font-bold text-indigo-300/80 tracking-widest uppercase">
                                {{ $customer->role ?? '高级用户' }}</p>
                        </div>
                    </div>

                    <div class="mt-10 space-y-4">
                        @php
                            $credit = $customer->credit_balance ?? 0;
                            $isDebt = $credit > 0;
                        @endphp

                        {{-- 挂单显示 --}}
                        <div class="bg-white/10 rounded-2xl p-5 border border-white/5">
                            <p
                                class="text-xs font-black uppercase tracking-[0.2em] {{ $isDebt ? 'text-red-200' : 'text-emerald-200' }}">
                                挂单额度
                            </p>

                            <div class="mt-1 flex items-baseline gap-1">

                                <span class="text-xl font-bold {{ $isDebt ? 'text-red-300' : 'text-emerald-300' }}">
                                    RM
                                </span>

                                <span
                                    class="text-3xl font-black tracking-tighter {{ $isDebt ? 'text-red-400' : 'text-emerald-400' }}">
                                    {{ $isDebt ? '-' : '' }}{{ number_format($credit, 2) }}
                                </span>

                            </div>
                        </div>

                        {{-- 详细资料 --}}
                        <div class="pt-2 space-y-4">
                            <div class="flex justify-between items-center px-1">
                                <span class="text-xs font-bold text-slate-400 uppercase">账号状态</span>
                                <span
                                    class="inline-flex h-2 w-2 rounded-full {{ $customer->is_active ? 'bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)]' : 'bg-rose-500' }}"></span>
                            </div>
                            <div class="flex justify-between items-center px-1">
                                <span class="text-xs font-bold text-slate-400 uppercase">全名</span>
                                <span class="text-xs font-black">{{ $customer->full_name ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 操作按钮 --}}
                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.customers.edit', $customer) }}"
                            class="h-12 flex items-center justify-center rounded-xl bg-white text-slate-900 text-xs font-black uppercase tracking-widest hover:bg-indigo-50 transition-all active:scale-95">
                            编辑资料
                        </a>
                        <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="w-full h-12 flex items-center justify-center rounded-xl text-xs font-black uppercase tracking-widest transition-all
                                        {{ $customer->is_active
                                            ? 'bg-rose-600 text-white hover:bg-rose-700'
                                            : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">

                                {{ $customer->is_active ? '停用账号' : '启用账号' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Insight Card --}}
            <div class="rounded-[2rem] bg-white border border-slate-100 p-8 shadow-sm">
                <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">顾客数据概览</p>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-2xl font-black text-slate-900">{{ count($orders) }}</p>
                        <p class="text-xs font-bold text-slate-400 uppercase">总预约次数</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900">100%</p>
                        <p class="text-xs font-bold text-slate-400 uppercase">完成率</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
