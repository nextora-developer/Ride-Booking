@extends('layouts.driver-app')

@section('title', '历史记录')

@section('header')
    <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">
        {{-- Back Button --}}
        <div class="absolute left-0 top-1/2 -translate-y-1/2">
            <a href="{{ route('driver.dashboard') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>

        {{-- Center Title --}}
        <div class="text-center">
            <h1 class="text-lg font-black text-slate-800 leading-none">历史记录</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">History Record</p>
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
        $q = $q ?? request('q', '');
        $status = $status ?? request('status', '');
        $counts = $counts ?? (object) ['total' => $orders->total() ?? ($orders->count() ?? 0)];
    @endphp

    <div class="space-y-6 pb-10">

        {{-- 1. 搜索与筛选 --}}
        <form method="GET" action="{{ route('driver.history.index') }}"
            class="bg-white p-3 rounded-[2rem] border border-slate-200
                   shadow-[0_12px_30px_rgba(15,23,42,0.06)]">
            <div class="flex flex-col md:flex-row gap-2">

                <div class="relative flex-1">
                    <input name="q" value="{{ $q }}" placeholder="搜索订单号或地点..."
                        class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-5
                               text-sm font-bold text-slate-800
                               shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-200
                               transition-all placeholder:text-slate-400" />
                </div>

                <div class="flex gap-2">
                    <select name="status"
                        class="h-12 flex-1 md:w-36 rounded-2xl border border-slate-200 bg-white px-4
                               text-xs font-black shadow-sm
                               focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-200 transition-all">
                        <option value="">全部状态</option>
                        <option value="ongoing" {{ $status === 'ongoing' ? 'selected' : '' }}>进行中</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>已完成</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>已取消</option>
                    </select>

                    <button type="submit"
                        class="h-12 w-12 flex items-center justify-center rounded-2xl bg-indigo-600 text-white
                               shadow-[0_14px_30px_rgba(79,70,229,0.25)]
                               active:scale-95 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <a href="{{ route('driver.history.index') }}"
                        class="h-12 px-4 flex items-center justify-center rounded-2xl bg-white
                               text-slate-600 font-black text-xs border border-slate-200
                               shadow-sm active:scale-95 transition">
                        重置
                    </a>
                </div>
            </div>
        </form>

        {{-- 2. 列表区域 --}}
        <div class="space-y-4">

            <div class="flex items-center justify-between px-2">
                <h3 class="text-sm font-black text-slate-900 tracking-widest">
                    历史订单
                    <span class="ml-1 text-slate-500 font-bold">({{ (int) ($counts->total ?? 0) }})</span>
                </h3>
            </div>

            @if ($orders->count())
                <div class="space-y-3">
                    @foreach ($orders as $o)
                        @php
                            $isCompleted = $o->status === 'completed';
                            $isCancelled = $o->status === 'cancelled';

                            $statusText = $isCompleted ? '已完成' : ($isCancelled ? '已取消' : '进行中');
                            $statusChip = $isCompleted
                                ? 'bg-emerald-100 text-emerald-700 border-emerald-200'
                                : ($isCancelled ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-indigo-100 text-indigo-700 border-indigo-200');

                            $pay = strtoupper($o->payment_type ?? '-');
                            $payZh = $pay === 'CASH' ? '现金' : ($pay === 'TRANSFER' ? '转账' : ($pay === 'CREDIT' ? '挂单' : $pay));

                            $pax = (int) ($o->pax ?? 1);
                            $amount = (float) ($o->amount ?? 0);
                        @endphp

                        <a href="{{ route('driver.history.show', $o) }}"
                            class="block bg-white p-5 rounded-[1.7rem]
                                   border border-slate-200
                                   shadow-[0_14px_35px_rgba(15,23,42,0.06)]
                                   hover:border-indigo-200 transition-all active:scale-[0.98]">

                            {{-- Top row --}}
                            <div class="flex justify-between items-start mb-4">
                                <div class="min-w-0">
                                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                        订单 #{{ $o->id }}
                                    </div>
                                    <div class="text-[11px] font-bold text-slate-600 mt-0.5">
                                        {{ optional($o->created_at)->format('d M, h:i A') }}
                                    </div>

                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black border {{ $statusChip }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-lg font-black text-slate-900 leading-none">
                                        RM {{ number_format($amount, 2) }}
                                    </div>
                                    <div class="text-xs font-black text-slate-500 mt-1">
                                        {{ $payZh }}
                                    </div>
                                </div>
                            </div>

                            {{-- Route mini timeline --}}
                            <div class="space-y-2 relative">
                                <div class="absolute left-[6px] top-[6px] bottom-[6px] w-[2px] bg-slate-200 rounded-full"></div>

                                <div class="flex items-center gap-3 pl-5">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></div>
                                    <div class="text-xs font-bold text-slate-700 truncate">
                                        {{ $o->pickup ?? '-' }}
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 pl-5">
                                    <div class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></div>
                                    <div class="text-xs font-bold text-slate-700 truncate">
                                        {{ $o->dropoff ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Bottom chips --}}
                            <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between">
                                <div class="flex gap-2">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-[10px] font-black text-slate-700">
                                        {{ $payZh }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-[10px] font-black text-slate-700">
                                        {{ $pax }} 人
                                    </span>
                                </div>

                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>

                        </a>
                    @endforeach
                </div>

                <div class="py-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="py-20 text-center bg-white rounded-[2rem] border border-slate-200 shadow-sm">
                    <div class="text-4xl mb-4">📭</div>
                    <div class="text-sm font-black text-slate-600 tracking-widest">暂无记录</div>
                    <div class="text-xs font-bold text-slate-400 mt-2">完成、取消或进行中的订单会在这里显示</div>
                </div>
            @endif

        </div>
    </div>
@endsection
