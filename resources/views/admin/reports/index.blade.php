@extends('layouts.admin-app')

@section('title', '报表')

@section('header')
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">报表中心</h1>
        <p class="mt-1 text-sm text-slate-500">财务与运营总览</p>
    </div>
@endsection

@section('content')
    @php
        $label = 'text-[11px] font-black text-slate-400 uppercase tracking-widest';
        $ctrl =
            'mt-2 h-11 w-full rounded-2xl border border-gray-200 bg-white px-4 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10';
        $card = 'rounded-3xl bg-white border border-gray-100 shadow-sm';
        $money = fn($v) => 'RM ' . number_format((float) $v, 2);
        $pct = function ($part, $total) {
            $t = max(1, (int) $total);
            return round(($part / $t) * 100, 1);
        };

        $hasFilter =
            request()->filled('from') ||
            request()->filled('to') ||
            request()->filled('shift') ||
            request()->filled('payment_type');

        // payment fallback
        $pCash = $paymentStats->get('cash');
        $pCredit = $paymentStats->get('credit');
        $pTransfer = $paymentStats->get('transfer');
        $sDay = $shiftStats->get('day');
        $sNight = $shiftStats->get('night');
    @endphp

    {{-- 筛选 + 导出 --}}
    <div class="{{ $card }} p-4 sm:p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-3">
                <div class="{{ $label }}">开始日期</div>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="{{ $ctrl }}">
            </div>

            <div class="lg:col-span-3">
                <div class="{{ $label }}">结束日期</div>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="{{ $ctrl }}">
            </div>

            <div class="lg:col-span-3">
                <div class="{{ $label }}">班次</div>
                <select name="shift" class="{{ $ctrl }}">
                    <option value="">全部班次</option>
                    <option value="day" @selected(($shift ?? '') === 'day')>白班</option>
                    <option value="night" @selected(($shift ?? '') === 'night')>夜班</option>
                </select>
            </div>

            <div class="lg:col-span-3">
                <div class="{{ $label }}">付款方式</div>
                <select name="payment_type" class="{{ $ctrl }}">
                    <option value="">全部付款</option>
                    <option value="cash" @selected(($paymentType ?? '') === 'cash')>现金</option>
                    <option value="credit" @selected(($paymentType ?? '') === 'credit')>挂单</option>
                    <option value="transfer" @selected(($paymentType ?? '') === 'transfer')>转账</option>
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-12 flex flex-col sm:flex-row gap-2 justify-end pt-1">
                <button
                    class="h-11 px-6 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                    应用筛选
                </button>

                @if ($hasFilter)
                    <a href="{{ route('admin.reports.index') }}"
                        class="h-11 inline-flex items-center justify-center px-6 rounded-2xl border border-gray-200 bg-white text-sm font-extrabold hover:bg-gray-50 transition">
                        重置
                    </a>
                @endif

                {{-- 导出（你有 route 再打开） --}}
                {{-- <a href="{{ route('admin.reports.export', request()->query()) }}" class="h-11 inline-flex items-center justify-center px-6 rounded-2xl border border-gray-200 bg-white text-sm font-extrabold hover:bg-gray-50 transition">导出 CSV</a> --}}
            </div>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-5 mb-8">

        {{-- 总订单 --}}
        <div class="{{ $card }} p-6 bg-slate-900 text-white border-none shadow-xl shadow-slate-200">
            <div class="text-[10px] uppercase text-slate-400 font-black tracking-[0.2em]">订单总数</div>
            <div class="mt-3 text-3xl text-slate-900 font-black tracking-tighter">
                {{ $totalOrders }}
            </div>
            <div class="mt-2 text-xs font-bold text-slate-400 flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $from->format('d M') }} — {{ $to->format('d M') }}
            </div>
        </div>

        {{-- 营业额 --}}
        <div class="{{ $card }} p-6 border-slate-100 hover:border-emerald-500/30 transition-colors group">
            <div class="text-[10px] uppercase text-slate-400 font-black tracking-[0.2em]">营业额</div>
            <div
                class="mt-3 text-3xl font-black tracking-tighter text-slate-900 group-hover:text-emerald-600 transition-colors">
                {{ $money($revenue) }}
            </div>
            <div class="mt-2 flex items-center gap-1 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">
                总收入
            </div>
        </div>

        {{-- 客单价 --}}
        <div class="{{ $card }} p-6 border-slate-100">
            <div class="text-[10px] uppercase text-slate-400 font-black tracking-[0.2em]">平均订单金额</div>
            <div class="mt-3 text-3xl font-black tracking-tighter text-slate-900">{{ $money($aov) }}</div>
            <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">客单价</div>
        </div>

        {{-- 已完成 --}}
        <div class="{{ $card }} p-6 border-slate-100 bg-emerald-50/20">
            <div class="text-[10px] uppercase text-emerald-600/70 font-black tracking-[0.2em]">已完成</div>
            <div class="mt-3 text-3xl font-black tracking-tighter text-emerald-700">{{ $completed }}</div>
            <div class="mt-2">
                <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700">
                    {{ $pct($completed, $totalOrders) }}%
                </span>
            </div>
        </div>

        {{-- 待处理 --}}
        <div class="{{ $card }} p-6 border-slate-100 bg-amber-50/20">
            <div class="text-[10px] uppercase text-amber-600/70 font-black tracking-[0.2em]">待处理</div>
            <div class="mt-3 text-3xl font-black tracking-tighter text-amber-700">{{ $pending }}</div>
            <div class="mt-2">
                <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-amber-100 text-amber-700">
                    {{ $pct($pending, $totalOrders) }}%
                </span>
            </div>
        </div>

        {{-- 已取消 --}}
        <div class="{{ $card }} p-6 border-slate-100 bg-rose-50/20">
            <div class="text-[10px] uppercase text-rose-600/70 font-black tracking-[0.2em]">已取消</div>
            <div class="mt-3 text-3xl font-black tracking-tighter text-rose-700">{{ $cancelled }}</div>
            <div class="mt-2">
                <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-rose-100 text-rose-700">
                    {{ $pct($cancelled, $totalOrders) }}%
                </span>
            </div>
        </div>

    </div>

    {{-- 趋势 + 状态分布 --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

        {{-- 左：每日趋势 --}}
        <div class="lg:col-span-7 {{ $card }} p-8 group">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-slate-900 rounded-full"></span>
                        每日表现
                    </h3>
                    <p class="text-[11px] text-slate-400 font-bold mt-1 uppercase">订单 & 营业额走势</p>
                </div>
                <div class="hidden sm:block text-right">
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">最高峰</span>
                    <div class="text-xs font-black text-slate-900 mt-0.5">
                        {{ $maxDailyOrders }} <span class="text-slate-300 mx-1">/</span> {{ $money($maxDailyAmount) }}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($daily as $row)
                    @php
                        $ordersW = $maxDailyOrders > 0 ? round(($row->orders / $maxDailyOrders) * 100) : 0;
                        $amtW = $maxDailyAmount > 0 ? round(((float) $row->amount / $maxDailyAmount) * 100) : 0;
                    @endphp
                    <div class="grid grid-cols-12 gap-4 items-center">
                        {{-- 日期 --}}
                        <div class="col-span-2">
                            <div class="text-[11px] font-black text-slate-900">
                                {{ \Illuminate\Support\Carbon::parse($row->d)->format('d M') }}
                            </div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                {{ \Illuminate\Support\Carbon::parse($row->d)->format('D') }}
                            </div>
                        </div>

                        {{-- 图表 --}}
                        <div class="col-span-10 space-y-2">
                            {{-- 订单条 --}}
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-1.5 rounded-full bg-slate-50 overflow-hidden">
                                    <div class="h-full bg-slate-900 rounded-full transition-all duration-700 group-hover:bg-indigo-600"
                                        style="width: {{ $ordersW }}%"></div>
                                </div>
                                <span
                                    class="text-[10px] font-black text-slate-400 w-6 text-right">{{ $row->orders }}</span>
                            </div>
                            {{-- 金额条 --}}
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-1.5 rounded-full bg-slate-50 overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-700 shadow-[0_0_8px_rgba(16,185,129,0.1)]"
                                        style="width: {{ $amtW }}%"></div>
                                </div>
                                <span
                                    class="text-[10px] font-black text-emerald-600 w-16 text-right">{{ $money($row->amount) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs font-black text-slate-400 uppercase tracking-widest">
                        没有记录
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 右：订单状态分布 --}}
        <div class="lg:col-span-5 {{ $card }} p-8 bg-slate-50/30 border-slate-100">
            <div class="mb-8">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">订单状态分布</h3>
                <p class="text-[11px] text-slate-400 font-bold mt-1 uppercase">各状态占比</p>
            </div>

            @php
                $total = max(1, (int) $totalOrders);
                $bars = [
                    [
                        'label' => '待派单',
                        'val' => $statusCounts['pending'] ?? 0,
                        'color' => 'bg-amber-400',
                        'ring' => 'ring-amber-400/20',
                    ],
                    [
                        'label' => '已派单',
                        'val' => $statusCounts['assigned'] ?? 0,
                        'color' => 'bg-indigo-500',
                        'ring' => 'ring-indigo-500/20',
                    ],
                    [
                        'label' => '前往中',
                        'val' => $statusCounts['on_the_way'] ?? 0,
                        'color' => 'bg-sky-400',
                        'ring' => 'ring-sky-400/20',
                    ],
                    [
                        'label' => '行程中',
                        'val' => $statusCounts['in_trip'] ?? 0,
                        'color' => 'bg-purple-500',
                        'ring' => 'ring-purple-500/20',
                    ],
                    [
                        'label' => '已完成',
                        'val' => $statusCounts['completed'] ?? 0,
                        'color' => 'bg-emerald-500',
                        'ring' => 'ring-emerald-500/20',
                    ],
                    [
                        'label' => '已取消',
                        'val' => $statusCounts['cancelled'] ?? 0,
                        'color' => 'bg-rose-500',
                        'ring' => 'ring-rose-500/20',
                    ],
                ];
            @endphp

            <div class="space-y-6">
                @foreach ($bars as $b)
                    @php $w = round(($b['val'] / $total) * 100); @endphp
                    <div class="group">
                        <div class="flex items-end justify-between mb-2.5">
                            <div class="flex items-center gap-2.5">
                                <div class="h-2 w-2 rounded-full {{ $b['color'] }} ring-4 {{ $b['ring'] }}"></div>
                                <span
                                    class="text-[11px] font-black text-slate-700 uppercase tracking-wider">{{ $b['label'] }}</span>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-xs font-black text-slate-900 tracking-tighter">{{ $b['val'] }}</span>
                                <span
                                    class="text-[9px] font-bold text-slate-400 ml-1 opacity-0 group-hover:opacity-100 transition-opacity">{{ $w }}%</span>
                            </div>
                        </div>
                        <div class="h-3 rounded-xl bg-white border border-slate-100 p-0.5 overflow-hidden shadow-sm">
                            <div class="h-full {{ $b['color'] }} rounded-lg transition-all duration-1000"
                                style="width: {{ $w }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">概览</span>
                <div class="flex -space-x-1.5">
                    @foreach (array_slice($bars, 0, 4) as $b)
                        <div class="h-5 w-5 rounded-full border-2 border-white {{ $b['color'] }}"></div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- 付款 + 班次 + 服务 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 mb-8">

        {{-- 付款方式 --}}
        <div class="lg:col-span-4 {{ $card }} p-7">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">付款方式</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">订单分布</p>
                </div>
                <div class="h-8 w-8 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>

            <div class="space-y-4">
                @foreach (['cash' => ['label' => '现金', 'icon' => '💵'], 'credit' => ['label' => '挂单', 'icon' => '💳'], 'transfer' => ['label' => '转账', 'icon' => '🏦']] as $k => $meta)
                    @php
                        $r = $paymentStats->get($k);
                        $cnt = (int) ($r->total ?? 0);
                        $amt = (float) ($r->amount ?? 0);
                    @endphp
                    <div
                        class="group flex items-center justify-between p-3 rounded-2xl border border-transparent hover:border-slate-100 hover:bg-slate-50/50 transition-all">
                        <div class="flex items-center gap-3">
                            <span class="text-lg">{{ $meta['icon'] }}</span>
                            <span
                                class="text-xs font-black text-slate-600 uppercase tracking-wide">{{ $meta['label'] }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black text-slate-900 leading-none">{{ $cnt }}</div>
                            <div class="text-[10px] font-bold text-emerald-600 mt-1">{{ $money($amt) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 班次 --}}
        <div class="lg:col-span-4 {{ $card }} p-7">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">班次表现</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">按时间统计</p>
                </div>
                <div class="h-8 w-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach (['day' => ['label' => '白班', 'color' => 'bg-amber-400', 'text' => 'text-amber-600'], 'night' => ['label' => '夜班', 'color' => 'bg-slate-900', 'text' => 'text-slate-400']] as $k => $meta)
                    @php
                        $r = $shiftStats->get($k);
                        $cnt = (int) ($r->total ?? 0);
                        $amt = (float) ($r->amount ?? 0);
                    @endphp
                    <div
                        class="flex flex-col p-4 rounded-[2rem] {{ $k === 'day' ? 'bg-amber-50/50' : 'bg-slate-50' }} border border-transparent">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="h-2 w-2 rounded-full {{ $meta['color'] }}"></div>
                            <span
                                class="text-[9px] font-black uppercase tracking-[0.1em] text-slate-500">{{ $meta['label'] }}</span>
                        </div>
                        <div class="text-2xl font-black text-slate-900 leading-none">{{ $cnt }}</div>
                        <div class="text-[10px] font-bold {{ $meta['text'] }} mt-2">{{ $money($amt) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 服务类型 --}}
        <div class="lg:col-span-4 {{ $card }} p-7">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">服务类型</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">服务表现</p>
                </div>
            </div>

            <div class="space-y-2 max-h-[180px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($serviceStats as $s)
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                        <div class="min-w-0">

                            @php
                                $serviceKey = strtolower(trim((string) ($s->service_type ?? '')));

                                $serviceLabel = match ($serviceKey) {
                                    'pickup_dropoff' => '接送',
                                    'charter' => '包车',
                                    'designated_driver' => '代驾',
                                    'purchase' => '代购',
                                    'big_car' => '大车',
                                    'driver_only' => '司机',
                                    default => '标准服务',
                                };
                            @endphp

                            <div class="text-[11px] font-black text-slate-800 truncate">
                                {{ $serviceLabel }}
                            </div>

                            <div class="text-[9px] font-bold text-slate-400 mt-0.5">
                                {{ $money($s->amount) }}
                            </div>
                        </div>

                        <div class="px-3 py-1 rounded-lg bg-slate-100 text-[11px] font-black text-slate-900">
                            {{ $s->total }}
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        没有服务记录
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- 司机 KPI --}}
    <div class="{{ $card }} overflow-hidden border-slate-100 shadow-xl shadow-slate-200/50">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-white">
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    司机表现
                </h3>
                <p class="text-[11px] text-slate-400 font-bold mt-1 uppercase">按完成订单数排名</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">上榜人数</span>
                <span class="text-xs font-black text-indigo-600">{{ $driverStats->count() }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th
                            class="px-8 py-4 text-left text-[10px] uppercase text-slate-400 font-black tracking-[0.15em] border-b border-slate-100">
                            排名 / 司机
                        </th>
                        <th
                            class="px-8 py-4 text-left text-[10px] uppercase text-slate-400 font-black tracking-[0.15em] border-b border-slate-100 text-center">
                            已完成
                        </th>
                        <th
                            class="px-8 py-4 text-left text-[10px] uppercase text-slate-400 font-black tracking-[0.15em] border-b border-slate-100">
                            营业额贡献
                        </th>
                        <th
                            class="px-8 py-4 text-left text-[10px] uppercase text-slate-400 font-black tracking-[0.15em] border-b border-slate-100">
                            平均效率
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse ($driverStats as $index => $d)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <span
                                        class="text-xs font-black {{ $index < 3 ? 'text-indigo-600' : 'text-slate-300' }} w-4">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div>
                                        <div
                                            class="text-sm font-black text-slate-900 group-hover:text-indigo-700 transition-colors">
                                            {{ optional($d->driver)->name ?? '未知司机' }}
                                        </div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                            ID: #DRV-{{ $d->driver_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-5 text-center">
                                <span
                                    class="inline-flex items-center justify-center h-8 w-12 rounded-xl bg-slate-900 text-white text-sm font-black tracking-tighter shadow-lg shadow-slate-200">
                                    {{ (int) $d->trips }}
                                </span>
                            </td>

                            <td class="px-8 py-5">
                                <div class="text-sm font-black text-slate-900">{{ $money($d->amount ?? 0) }}</div>
                                <div class="w-24 h-1 bg-slate-100 rounded-full mt-2 overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 70%"></div>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                @php $m = (int) round($d->avg_mins ?? 0); @endphp
                                <div class="flex items-center gap-2">
                                    <div
                                        class="h-1.5 w-1.5 rounded-full {{ $m < 30 ? 'bg-emerald-500' : ($m < 60 ? 'bg-amber-400' : 'bg-rose-500') }}">
                                    </div>
                                    <span class="text-xs font-black text-slate-700">
                                        {{ $m > 0 ? $m . ' 分钟' : '--' }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="h-12 w-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mb-3">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">暂无表现记录</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
