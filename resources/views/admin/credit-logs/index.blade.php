@extends('layouts.admin-app')

@section('title', '信用记录')

@section('header')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">信用记录</h1>
            <p class="mt-1 text-sm text-slate-500 font-semibold">
                追踪所有信用额调整与余额变动记录。
            </p>
        </div>
    </div>
@endsection

@section('content')

    {{-- Filter Section --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.credit.logs.index') }}"
            class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">

                {{-- Search --}}
                <div class="relative w-full sm:w-96">
                    <input name="q" value="{{ $q ?? '' }}" type="text" placeholder="搜索客户姓名..."
                        class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 pr-10 text-sm font-semibold
                               focus:ring-4 focus:ring-black/5 focus:border-black outline-none">

                    <svg class="h-5 w-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                        <circle cx="11" cy="11" r="7" />
                    </svg>
                </div>

                {{-- Type Filter --}}
                <select name="type"
                    class="w-full sm:w-44 h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-extrabold focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                    <option value="">全部类型</option>
                    <option value="add" @selected(($type ?? '') === 'add')>增加</option>
                    <option value="deduct" @selected(($type ?? '') === 'deduct')>扣除</option>
                    <option value="clear" @selected(($type ?? '') === 'clear')>清零</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    class="h-11 px-5 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                    应用筛选
                </button>

                @if (!empty($q) || !empty($type))
                    <a href="{{ route('admin.credit.logs.index') }}"
                        class="py-3 px-5 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                        清除
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="text-sm font-extrabold text-slate-900">
                信用记录明细
                <span class="ml-2 text-xs font-black text-slate-500">
                    共 {{ $logs->total() }} 条
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-black uppercase tracking-widest text-slate-400">
                        <th class="px-5 sm:px-6 py-3">客户</th>
                        <th class="px-5 sm:px-6 py-3">调整前</th>
                        <th class="px-5 sm:px-6 py-3">变动</th>
                        <th class="px-5 sm:px-6 py-3">调整后</th>
                        <th class="px-5 sm:px-6 py-3">操作</th>
                        <th class="px-5 sm:px-6 py-3">操作者</th>
                        <th class="px-5 sm:px-6 py-3">日期</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/60">

                            {{-- Customer --}}
                            <td class="px-5 sm:px-6 py-4">
                                <div class="font-extrabold text-slate-900">
                                    {{ $log->customer?->full_name }}
                                </div>
                                <div class="text-xs text-slate-500 font-semibold">
                                    ID: {{ $log->customer_id }}
                                </div>
                            </td>

                            {{-- Before --}}
                            <td class="px-5 sm:px-6 py-4 font-semibold text-slate-900">
                                RM {{ number_format($log->before, 2) }}
                            </td>

                            {{-- Change --}}
                            <td
                                class="px-5 sm:px-6 py-4 font-extrabold
                                {{ $log->change >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $log->change >= 0 ? '+' : '' }}
                                RM {{ number_format($log->change, 2) }}
                            </td>

                            {{-- After --}}
                            <td class="px-5 sm:px-6 py-4 font-semibold text-slate-900">
                                RM {{ number_format($log->after, 2) }}
                            </td>

                            {{-- Action Badge --}}
                            <td class="px-5 sm:px-6 py-4">
                                @php
                                    $change = (float) $log->change;
                                @endphp

                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black
        @if ($log->action === 'clear') bg-gray-100 text-gray-700
        @elseif ($log->action === 'order_completed')
            bg-indigo-100 text-indigo-700
        @elseif ($change > 0)
            bg-emerald-100 text-emerald-800
        @elseif ($change < 0)
            bg-rose-100 text-rose-700
        @else
            bg-slate-100 text-slate-700 @endif
        ">

                                    @if ($log->action === 'clear')
                                        清零
                                    @elseif ($log->action === 'order_completed')
                                        行程完成
                                    @elseif ($change > 0)
                                        增加
                                    @elseif ($change < 0)
                                        扣除
                                    @else
                                        更新
                                    @endif
                                </span>
                            </td>

                            {{-- By --}}
                            <td class="px-5 sm:px-6 py-4 text-sm font-semibold text-slate-900">
                                {{ $log->manager?->name ?? '系统' }}
                            </td>

                            {{-- Date --}}
                            <td class="px-5 sm:px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ $log->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-slate-500 font-semibold">
                                    {{ $log->created_at->format('h:i A') }}
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 sm:px-6 py-10 text-center text-sm text-slate-500 font-semibold">
                                没有找到信用记录。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 sm:px-6 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>

    </div>

@endsection
