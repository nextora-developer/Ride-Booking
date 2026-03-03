@extends('layouts.admin-app')

@section('title', '老板仪表板')

@section('header')
    <div class="relative px-2">

        {{-- Mobile Header --}}
        <div class="md:hidden flex items-center justify-between h-14">

            <div>
                <h1 class="text-lg font-black text-slate-900">
                    总控中心
                </h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    管理后台
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-black text-white shadow active:scale-90 transition-transform"
                title="刷新">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
            </a>

        </div>

        {{-- Desktop Header --}}
        <div class="hidden md:flex items-end justify-between">

            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                    总控中心
                </h1>

                <p class="mt-2 text-sm text-slate-500 font-medium">
                    订单总览、派单状态、司机情况与付款方式（现金 / 挂单 / 转账）一目了然。
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-2xl bg-black text-white text-sm font-bold hover:bg-slate-900 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
                刷新
            </a>

        </div>

    </div>
@endsection

@section('content')

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- 待派单 --}}
        <div class="group rounded-[2rem] bg-amber-50 border border-amber-100 p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div class="text-[10px] font-black tracking-[0.15em] uppercase text-amber-600/80">待派单</div>
                <span class="text-xl">⏳</span>
            </div>
            <div class="mt-3 text-4xl font-black text-amber-900">{{ number_format($pending) }}</div>
            <div class="mt-2 text-[11px] font-bold text-amber-600/70 uppercase">需要尽快处理</div>
        </div>

        {{-- 行程中 --}}
        <div
            class="group rounded-[2rem] bg-indigo-50 border border-indigo-100 p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div class="text-[10px] font-black tracking-[0.15em] uppercase text-indigo-600/80">行程中</div>
                <span class="text-xl">🚗</span>
            </div>
            <div class="mt-3 text-4xl font-black text-indigo-900">{{ number_format($active) }}</div>
            <div class="mt-2 text-[11px] font-bold text-indigo-600/70 uppercase">司机正在执行</div>
        </div>

        {{-- 今日完成 --}}
        <div
            class="group rounded-[2rem] bg-emerald-50 border border-emerald-100 p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div class="text-[10px] font-black tracking-[0.15em] uppercase text-emerald-600/80">今日完成</div>
                <span class="text-xl">✅</span>
            </div>
            <div class="mt-3 text-4xl font-black text-emerald-900">{{ number_format($todayCompleted) }}</div>
            <div class="mt-2 text-[11px] font-bold text-emerald-600/70 uppercase">今日战绩</div>
        </div>

        {{-- 30天总量 --}}
        <div class="group rounded-[2rem] bg-slate-900 p-6 shadow-lg shadow-slate-200 transition-all text-white">
            <div class="flex items-center justify-between">
                <div class="text-[10px] font-black tracking-[0.15em] uppercase text-slate-400">近30天总量</div>
                <span class="text-xl opacity-50">📊</span>
            </div>
            <div class="mt-3 text-4xl font-black">{{ number_format($total30) }}</div>
            <div class="mt-2 text-[11px] font-bold text-slate-400 uppercase">月度总览</div>
        </div>
    </div>

    {{-- Mobile --}}
    <div class="mt-8 md:hidden">
        <div class="grid grid-cols-3 gap-4">

            <div class="rounded-2xl bg-white border border-slate-100 p-4 shadow-sm text-center">
                <div class="h-10 w-10 mx-auto rounded-xl bg-slate-100 flex items-center justify-center text-lg">
                    👥
                </div>
                <div class="mt-2 text-[9px] font-black text-slate-400 uppercase">
                    司机总数
                </div>
                <div class="text-lg font-black text-slate-900">
                    {{ $driversTotal }}
                </div>
            </div>

            <div class="rounded-2xl bg-white border border-slate-100 p-4 shadow-sm text-center">
                <div
                    class="h-10 w-10 mx-auto rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-lg">
                    ☀️
                </div>
                <div class="mt-2 text-[9px] font-black text-slate-400 uppercase">
                    白班
                </div>
                <div class="text-lg font-black text-slate-900">
                    {{ $driversDay }}
                </div>
            </div>

            <div class="rounded-2xl bg-white border border-slate-100 p-4 shadow-sm text-center">
                <div class="h-10 w-10 mx-auto rounded-xl bg-indigo-900 text-white flex items-center justify-center text-lg">
                    🌙
                </div>
                <div class="mt-2 text-[9px] font-black text-slate-400 uppercase">
                    夜班
                </div>
                <div class="text-lg font-black text-slate-900">
                    {{ $driversNight }}
                </div>
            </div>

        </div>
    </div>

    {{-- Desktop --}}
    <div class="mt-8 hidden md:block">
        <div class="grid grid-cols-3 gap-5">
            {{-- 总司机 --}}
            <div class="flex items-center gap-5 p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm">
                <div
                    class="h-14 w-14 rounded-2xl bg-slate-50 flex items-center justify-center text-2xl shadow-inner shrink-0">
                    👥
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider">注册司机</div>
                    <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $driversTotal }}</div>
                </div>
            </div>

            {{-- 白班 --}}
            <div
                class="flex items-center gap-5 p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm relative overflow-hidden">
                <div
                    class="h-14 w-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl shrink-0">
                    ☀️
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider">白班</div>
                    <div class="text-2xl font-black text-slate-900 mt-0.5">
                        {{ $driversDay }}
                        <span class="text-xs font-bold text-slate-400 ml-1">值班中</span>
                    </div>
                </div>
            </div>

            {{-- 夜班 --}}
            <div
                class="flex items-center gap-5 p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm relative overflow-hidden">
                <div
                    class="h-14 w-14 rounded-2xl bg-indigo-900 text-indigo-100 flex items-center justify-center text-2xl shrink-0">
                    🌙
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider">夜班</div>
                    <div class="text-2xl font-black text-slate-900 mt-0.5">
                        {{ $driversNight }}
                        <span class="text-xs font-bold text-slate-400 ml-1">值班中</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="mt-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Latest Orders --}}
        <div class="xl:col-span-2 rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <div class="text-sm font-extrabold text-slate-900">最新订单</div>
                    <div class="text-xs text-slate-500 font-medium mt-1">
                        实时查看派单状态与付款方式
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex items-center justify-center h-10 px-4 rounded-2xl bg-black text-white text-sm font-bold hover:bg-slate-900 transition">
                        查看全部
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-black tracking-widest uppercase text-slate-400">
                            <th class="px-6 py-4">订单</th>
                            <th class="px-6 py-4">服务</th>
                            <th class="px-6 py-4">路线</th>
                            <th class="px-6 py-4">付款</th>
                            <th class="px-6 py-4">状态</th>
                            <th class="px-6 py-4 text-right">操作</th>
                        </tr>
                    </thead>

                    @php
                        $badge = function ($status) {
                            return match (strtolower((string) $status)) {
                                'pending' => 'bg-amber-50 text-amber-700',
                                'assigned' => 'bg-yellow-50 text-yellow-700',
                                'on_the_way' => 'bg-blue-50 text-blue-700',
                                'arrived' => 'bg-indigo-50 text-indigo-700',
                                'in_trip' => 'bg-violet-50 text-violet-700',
                                'completed' => 'bg-green-50 text-green-700',
                                'cancelled' => 'bg-gray-100 text-gray-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        };

                        $payBadge = function ($pay) {
                            return match (strtolower((string) $pay)) {
                                'cash' => 'bg-black text-white',
                                'credit' => 'bg-rose-50 text-rose-700',
                                'transfer' => 'bg-emerald-50 text-emerald-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        };

                        $orderNo = fn($id) => 'ORD-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);

                        $serviceLabel = function ($service) {
                            $s = strtolower((string) $service);
                            return match ($s) {
                                'pickup_dropoff' => '接送',
                                'charter' => '包车',
                                'designated_driver' => '代驾',
                                'purchase' => '代购',
                                'big_car' => '大车',
                                'driver_only' => '司机',
                                default => $service ?: '—',
                            };
                        };

                        $statusText = function ($status) {
                            return match (strtolower((string) $status)) {
                                'pending' => '待派单',
                                'assigned' => '已指派',
                                'on_the_way' => '前往接送',
                                'arrived' => '已到起点',
                                'in_trip' => '行程中',
                                'completed' => '已完成',
                                'cancelled' => '已取消',
                                default => strtoupper((string) $status ?: '—'),
                            };
                        };

                        $payText = function ($pay) {
                            return match (strtolower((string) $pay)) {
                                'cash' => '现金',
                                'credit' => '挂单',
                                'transfer' => '转账',
                                default => strtoupper((string) $pay ?: '—'),
                            };
                        };
                    @endphp

                    <tbody class="divide-y divide-gray-100">
                        @foreach ($latestOrders as $o)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-slate-900">
                                        {{ $orderNo($o->id) }}
                                    </div>

                                    <div class="text-xs text-slate-500 font-medium mt-1">
                                        {{ optional($o->created_at)->format('d M Y, h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $serviceLabel($o->service_type ?? '-') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-900 font-semibold">
                                        {{ $o->pickup ?? '-' }} → {{ $o->dropoff ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-500 font-medium mt-1">
                                        顾客：{{ optional($o->customer)->name ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $payBadge($o->payment_type) }}">
                                        {{ $payText($o->payment_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black {{ $badge($o->status) }}">
                                        {{ $statusText($o->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $o) }}"
                                        class="inline-flex items-center justify-center h-9 px-3 rounded-xl bg-white border border-gray-200 text-sm font-bold hover:bg-gray-50 transition">
                                        查看
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="space-y-6">
            {{-- Payment Mix --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6">
                <div class="text-sm font-extrabold text-slate-900">
                    今日付款占比
                </div>
                <div class="text-xs text-slate-500 font-medium mt-1">
                    以今日订单为统计
                </div>

                <div class="mt-5 space-y-4">
                    @forelse ($mix as $m)
                        @php
                            $key = strtolower(trim((string) ($m['key'] ?? ($m['type'] ?? ($m['name'] ?? '')))));

                            $label = match ($key) {
                                'cash' => '现金',
                                'credit' => '挂账',
                                'transfer' => '转账',
                                default => $m['name'] ?? '未知',
                            };
                        @endphp

                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="font-bold text-slate-900">
                                    {{ $label }}
                                    <span class="ml-2 text-xs text-slate-400 font-semibold">
                                        （{{ $m['count'] }} 单）
                                    </span>
                                </div>

                                <div class="text-slate-500 font-semibold">
                                    {{ $m['pct'] }}%
                                </div>
                            </div>

                            <div class="mt-2 h-2.5 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full {{ $m['class'] }} transition-all duration-500"
                                    style="width: {{ $m['pct'] }}%">
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="text-sm text-slate-500 font-semibold">
                            今天暂无订单。
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Alerts --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6">
                <div class="text-sm font-extrabold text-slate-900">提醒</div>
                <div class="text-xs text-slate-500 font-medium mt-1">
                    需要你关注的事项
                </div>

                <div class="mt-4 space-y-3 text-sm">

                    {{-- Pending Orders --}}
                    @if ($pending > 0)
                        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                            class="block rounded-2xl bg-rose-50 border border-rose-100 p-4 hover:bg-rose-100 transition">
                            <div class="font-extrabold text-rose-800">
                                ⚠️ {{ $pending }} 单待派单
                            </div>
                            <div class="text-rose-700 mt-1">
                                这些订单还未指派司机，点击立即派单。
                            </div>
                        </a>
                    @else
                        <div class="rounded-2xl bg-green-50 border border-green-100 p-4">
                            <div class="font-extrabold text-green-800">
                                ✅ 没有待派单
                            </div>
                            <div class="text-green-700 mt-1">
                                所有订单都已完成指派。
                            </div>
                        </div>
                    @endif

                    {{-- Pending Credit Payments --}}
                    @if ($pendingCredit > 0)
                        <a href="{{ route('admin.orders.index', ['payment_type' => 'credit']) }}"
                            class="block rounded-2xl bg-yellow-50 border border-yellow-100 p-4 hover:bg-yellow-100 transition">
                            <div class="font-extrabold text-yellow-800">
                                ⏳ {{ $pendingCredit }} 单挂单待处理
                            </div>
                            <div class="text-yellow-700 mt-1">
                                请核对公司结算或顾客挂单额度。
                            </div>
                        </a>
                    @else
                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                            <div class="font-extrabold text-slate-800">
                                ✔️ 没有挂单待处理
                            </div>
                            <div class="text-slate-600 mt-1">
                                今日挂单状态已清算完成。
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
