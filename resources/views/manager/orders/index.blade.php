@extends('layouts.manager-app')

@section('title', '订单列表')

@section('header')
    <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">

        {{-- Back Button --}}
        <div class="absolute left-0 top-1/2 -translate-y-1/2">
            <a href="{{ route('manager.dashboard') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>

        {{-- Center Title --}}
        <div class="text-center">
            <h1 class="text-lg font-black text-slate-800 leading-none">所有订单</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">All Order</p>
        </div>

        {{-- Refresh Button --}}
        <div class="absolute right-0 top-1/2 -translate-y-1/2">
            <button onclick="window.location.reload()"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm hover:bg-slate-50">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>

            </button>
        </div>

    </div>
@endsection

@section('content')
    @php
        $statusConfig = fn($v) => match ($v) {
            'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'cancelled' => 'bg-rose-50 text-rose-500 border-rose-100',
            'assigned', 'on_the_way', 'arrived', 'in_trip' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
            default => 'bg-slate-50 text-slate-500 border-slate-100',
        };

        $statusText = fn($v) => match ($v) {
            'pending' => '待派单',
            'assigned' => '已派单',
            'on_the_way' => '司机在路上',
            'arrived' => '司机已到达',
            'in_trip' => '行程中',
            'completed' => '已完成',
            'cancelled' => '已取消',
            default => str_replace('_', ' ', $v),
        };

        $serviceLabel = fn($v) => match ($v) {
            'pickup_dropoff' => '接送',
            'charter' => '包车',
            'designated_driver' => '代驾',
            'purchase' => '代购',
            'big_car' => '大车',
            'driver_only' => '司机',
            default => '服务',
        };

        $shiftText = fn($v) => match (strtolower((string) $v)) {
            'day', 'morning', '早班' => '早班',
            'night', '晚班' => '晚班',
            default => is_null($v) || $v === '' ? '-' : (string) $v,
        };

        $paymentText = fn($v) => match (strtolower((string) $v)) {
            'cash', '现金' => '现金',
            'credit', '挂单' => '挂单',
            'transfer', '转账' => '转账',
            default => is_null($v) || $v === '' ? '现金' : strtoupper((string) $v),
        };

        $status = request('status', 'all');
        $search = request('search', '');
    @endphp

    {{-- App Style Filter Chips --}}
    <div class="px-2 mb-6 mt-2 overflow-x-auto no-scrollbar flex items-center gap-2">
        @foreach (['all' => '全部', 'pending' => '待派单', 'assigned' => '进行中', 'completed' => '已完成'] as $key => $label)
            <a href="{{ route('manager.orders.index', array_filter(['status' => $key, 'search' => $search])) }}"
                class="shrink-0 px-5 py-2 rounded-full text-xs font-black tracking-wider transition-all {{ $status === $key ? 'bg-slate-800 text-white shadow-lg shadow-slate-200' : 'bg-white text-slate-400 border border-slate-100' }}">
                {{ $label }} </a>
        @endforeach
    </div>

    {{-- Search Bar --}}
    <form method="GET" class="mt-6 mb-6 px-2">
        <input type="hidden" name="status" value="{{ $status }}">

        <div
            class="bg-white rounded-[2rem] p-2 border border-slate-200 shadow-[0_10px_26px_rgba(15,23,42,0.08)]
           flex items-center gap-2
           focus-within:border-slate-900 focus-within:ring-4 focus-within:ring-slate-900/10
           transition-all">

            {{-- Icon --}}
            <div class="pl-3 text-slate-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            {{-- Input --}}
            <input name="search" value="{{ $search }}" placeholder="搜索单号、地点、司机..."
                class="flex-1 border-none bg-transparent py-3 text-sm font-bold text-slate-900
               focus:ring-0 placeholder:text-slate-400" />

            {{-- Clear --}}
            @if ($search)
                <a href="{{ route('manager.orders.index', ['status' => $status]) }}"
                    class="h-10 w-10 flex items-center justify-center rounded-full
                   bg-slate-100 text-slate-500 hover:text-rose-600 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif

            {{-- Submit --}}
            <button
                class="h-11 px-6 rounded-2xl bg-slate-900 text-white font-black text-sm
               shadow-[0_14px_30px_rgba(15,23,42,0.24)]
               active:scale-95 transition whitespace-nowrap">
                搜索
            </button>

        </div>
    </form>

    {{-- Order Cards --}}
    <div class="space-y-4 pb-7">
        @forelse ($orders as $o)
            @php
                $when = $o->schedule_type === 'scheduled' && $o->scheduled_at ? $o->scheduled_at : $o->created_at;
                $isScheduled = $o->schedule_type === 'scheduled';
            @endphp

            <a href="{{ route('manager.orders.show', $o) }}"
                class="block bg-white rounded-[2rem] p-6
                   shadow-[0_12px_30px_rgba(15,23,42,0.08)]
                   border border-slate-200
                   active:scale-[0.97] active:bg-slate-50 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-lg font-black text-slate-900 tracking-tighter truncate">
                            {{ $o->user?->full_name ?? ($o->user?->name ?? '未知顾客') }}
                        </span>

                        <span
                            class="px-2 py-0.5 rounded-lg border-2 text-[11px] font-black uppercase tracking-widest
                                 {{ $statusConfig($o->status) }}">
                            {{ $statusText($o->status) }}
                        </span>

                        @if ($isScheduled)
                            <span
                                class="px-2 py-0.5 rounded-lg border text-[9px] font-black uppercase tracking-widest
                                   bg-slate-100 text-slate-700 border-slate-200">
                                预约
                            </span>
                        @endif
                    </div>

                    <div class="text-right shrink-0">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">班次</div>
                        <div class="text-xs font-bold text-slate-700 mt-1 capitalize">{{ $shiftText($o->shift) }}</div>
                    </div>
                </div>

                {{-- Route Visual --}}
                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-[5px] top-3 bottom-3 w-px bg-slate-200"></div>

                    <div class="space-y-3">
                        {{-- Pickup --}}
                        <div class="relative flex items-start gap-3">
                            <div class="relative z-10 shrink-0 pt-1">
                                <div class="h-2.5 w-2.5 rounded-full border-2 border-slate-400 bg-white"></div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    上车地点
                                </div>
                                <div class="text-sm font-bold text-slate-500 truncate leading-tight mt-0.5">
                                    {{ $o->pickup }}
                                </div>
                            </div>
                        </div>

                        {{-- Dropoffs --}}
                        @if (!empty($o->dropoffs))
                            @foreach ($o->dropoffs as $i => $point)
                                <div class="relative flex items-start gap-3">
                                    <div class="relative z-10 shrink-0 pt-1">
                                        <div
                                            class="h-2.5 w-2.5 rounded-full {{ $loop->last ? 'bg-emerald-600' : 'bg-slate-900' }}">
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                            {{ $loop->last ? '最终目的地' : '下车点 ' . ($i + 1) }}
                                        </div>
                                        <div
                                            class="text-sm {{ $loop->last ? 'font-black text-slate-900' : 'font-bold text-slate-700' }} truncate leading-tight mt-0.5">
                                            {{ $point }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- 旧系统兼容 --}}
                            <div class="relative flex items-start gap-3">
                                <div class="relative z-10 shrink-0 pt-1">
                                    <div class="h-2.5 w-2.5 rounded-full bg-emerald-600"></div>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        目的地
                                    </div>
                                    <div class="text-sm font-black text-slate-900 truncate leading-tight mt-0.5">
                                        {{ $o->dropoff }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-200 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs shrink-0">👤
                        </div>
                        <div class="min-w-0">
                            <div class="text-[11px] font-black text-slate-400 uppercase leading-none">司机</div>
                            <div class="text-xs font-black text-slate-800 mt-0.5 truncate">
                                {{ $o->driver?->name ?? '未指派' }}
                            </div>
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <div class="text-xs font-black text-indigo-700 uppercase tracking-widest mb-0.5">
                            {{ $serviceLabel($o->service_type) }}
                        </div>

                        <div class="text-xs font-bold text-slate-600">
                            {{ $when->format('h:i A, d M') }}
                        </div>

                        <div class="mt-1 text-xs font-black text-slate-500 uppercase tracking-widest">
                            付款：{{ $paymentText($o->payment_type ?? 'cash') }}
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="py-24 flex flex-col items-center justify-center">
                {{-- 动态扫描视觉 --}}
                <div class="relative mb-8">
                    {{-- 扩散光晕 --}}
                    <div class="absolute inset-0 rounded-full bg-slate-200 animate-ping opacity-20"></div>

                    {{-- 核心图标 --}}
                    <div
                        class="relative h-24 w-24 rounded-full bg-white border-4 border-slate-100 shadow-[0_10px_26px_rgba(15,23,42,0.08)] flex items-center justify-center text-3xl">
                        <span class="grayscale opacity-40 transition-all">🔍</span>
                    </div>

                    {{-- 装饰点 --}}
                    <div class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-amber-400 border-4 border-white"></div>
                </div>

                {{-- 文字引导 --}}
                <div class="text-center px-6">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">未搜索到相关订单</h3>
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span class="h-px w-4 bg-slate-300"></span>
                        <p class="text-[11px] text-slate-500 font-black uppercase tracking-widest italic">No Records Found
                        </p>
                        <span class="h-px w-4 bg-slate-300"></span>
                    </div>

                    <p class="mt-4 text-xs text-slate-600 font-bold leading-relaxed max-w-[240px] mx-auto">
                        我们没能在那叠厚厚的工单里找到它。<br>请检查关键词是否正确，或尝试更换关键字。
                    </p>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        <div class="px-2 mt-6">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- Style hack for no-scrollbar --}}
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
