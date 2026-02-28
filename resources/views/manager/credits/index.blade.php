@extends('layouts.manager-app')

@section('title', '挂单管理')

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
            <h1 class="text-lg font-black text-slate-800 leading-none">挂单管理</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Credit Control</p>
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
    <div class="max-w-5xl mx-auto pb-24 px-3">

        <div class="grid grid-cols-2 gap-4 mt-6">
            {{-- 有挂单顾客卡片 --}}
            <div
                class="bg-white rounded-[2.5rem] p-6 border border-slate-200 shadow-[0_12px_28px_rgba(15,23,42,0.08)] relative overflow-hidden group">
                {{-- 背景装饰：大图标隐约可见 --}}
                <div
                    class="absolute -right-2 -bottom-2 text-6xl opacity-[0.06] group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 select-none">
                    👤
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-slate-900"></div>
                        <div class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">挂单顾客</div>
                    </div>
                    <div class="flex items-baseline gap-1 mt-2">
                        <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalCustomers }}</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase">位</div>
                    </div>
                </div>
            </div>

            {{-- 总挂单金额卡片 --}}
            <div
                class="bg-rose-100/35 rounded-[2.5rem] p-6 border border-rose-200/60 shadow-[0_12px_28px_rgba(15,23,42,0.08)] relative overflow-hidden group">
                {{-- 背景装饰：警告色调的装饰物 --}}
                <div
                    class="absolute -right-2 -bottom-2 text-6xl opacity-[0.12] group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 select-none">
                    💰
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-rose-600 animate-pulse"></div>
                        <div class="text-xs font-black text-rose-600/70 uppercase tracking-[0.2em]">总挂单金额</div>
                    </div>
                    <div class="flex items-baseline gap-0.5 mt-2">
                        <div class="text-sm font-black text-rose-700 self-start mt-1">RM</div>
                        <div class="text-3xl font-black text-rose-700 tracking-tighter">
                            {{ number_format($totalCredit, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Bar: Modernized --}}
        <form method="GET" class="mt-6">
            <div
                class="bg-white rounded-[2rem] p-2 border border-slate-200 shadow-[0_10px_26px_rgba(15,23,42,0.08)] flex items-center gap-2
                       focus-within:border-slate-900 focus-within:ring-4 focus-within:ring-slate-900/10 transition-all">

                <div class="pl-3 text-slate-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex-1 relative">
                    <input name="search" value="{{ request('search') }}" placeholder="搜索顾客姓名、电话..."
                        class="w-full border-none bg-transparent py-3 text-sm font-bold text-slate-900 focus:ring-0 placeholder:text-slate-400" />
                </div>

                @if (request('search'))
                    <a href="{{ route('manager.credits.index') }}"
                        class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:text-rose-600 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif

                <button
                    class="h-12 px-6 rounded-2xl bg-slate-900 text-white font-black text-sm
                           shadow-[0_14px_30px_rgba(15,23,42,0.22)]
                           active:scale-95 transition">
                    搜索
                </button>
            </div>
        </form>

        <div class="mt-6 space-y-3">
            @forelse($customers as $c)
                <div
                    class="bg-white rounded-3xl p-5 border border-slate-200 shadow-[0_12px_28px_rgba(15,23,42,0.08)]">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-10 w-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-sm
                                           shadow-[0_12px_26px_rgba(15,23,42,0.18)]">
                                    {{ strtoupper(substr($c->full_name ?? $c->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-black text-slate-900 truncate">
                                        {{ $c->full_name ?? '-' }}
                                    </div>
                                    <div class="text-xs font-semibold text-slate-600 truncate">
                                        {{ $c->phone ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest">当前挂单</div>
                            <div class="text-xl font-black text-rose-700 mt-1">
                                RM {{ number_format($c->credit_balance, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-stretch gap-3">

                        {{-- Update --}}
                        <form method="POST" action="{{ route('manager.credits.update', $c) }}"
                            class="flex-1 min-w-0 flex gap-2">
                            @csrf
                            @method('PATCH')

                            <input type="number" step="0.01" min="0" name="credit_balance"
                                value="{{ old('credit_balance', $c->credit_balance) }}" placeholder="输入新挂单金额"
                                class="min-w-0 flex-1 rounded-2xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-bold text-slate-900
                                       focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10
                                       transition-all placeholder:text-slate-400" />

                            <button
                                class="shrink-0 px-4 py-3 rounded-2xl bg-slate-900 text-white font-black text-sm
                                       shadow-[0_14px_30px_rgba(15,23,42,0.22)]
                                       active:scale-95 transition whitespace-nowrap">
                                更新
                            </button>
                        </form>

                        {{-- Clear --}}
                        <form method="POST" action="{{ route('manager.credits.clear', $c) }}"
                            onsubmit="return confirm('确定要清零此顾客挂单吗？');" class="shrink-0">
                            @csrf
                            <button
                                class="h-full px-4 py-3 rounded-2xl bg-rose-100/60 text-rose-800 font-black text-sm border border-rose-200/60
                                       active:scale-95 transition whitespace-nowrap hover:bg-rose-100">
                                清零
                            </button>
                        </form>

                    </div>
                </div>
            @empty
                <div
                    class="relative overflow-hidden bg-white rounded-[3rem] p-16 border border-slate-200 text-center shadow-[0_14px_34px_rgba(15,23,42,0.06)] group">
                    {{-- 背景装饰圆圈 - 增加层次感 --}}
                    <div
                        class="absolute -top-12 -right-12 h-40 w-40 bg-emerald-100/55 rounded-full opacity-60 group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div
                        class="absolute -bottom-8 -left-8 h-24 w-24 bg-slate-100/80 rounded-full opacity-70 group-hover:-translate-y-4 transition-transform duration-700">
                    </div>

                    <div class="relative z-10 flex flex-col items-center">
                        {{-- 图标容器 --}}
                        <div
                            class="mb-6 h-24 w-24 rounded-[2.5rem] bg-emerald-100/60 border border-emerald-200/60 flex items-center justify-center text-4xl
                                   shadow-[inset_0_0_0_1px_rgba(255,255,255,0.6)]">
                            <span class="filter drop-shadow-sm group-hover:scale-125 transition-transform duration-300">✨</span>
                        </div>

                        {{-- 文字内容 --}}
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">账目已清空</h3>
                        <p class="mt-2 text-sm text-slate-600 font-bold max-w-[220px] leading-relaxed">
                            目前没有任何挂单顾客 <br>
                            <span class="text-emerald-600 italic">Excellent!</span> 所有的账单都已结清。
                        </p>

                        {{-- 引导动作（可选） --}}
                        <button
                            class="mt-8 px-6 py-2.5 rounded-2xl bg-slate-900 text-white text-[11px] font-black uppercase tracking-[0.2em]
                                   shadow-[0_16px_38px_rgba(15,23,42,0.22)]
                                   active:scale-95 transition-all">
                            刷新列表
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
