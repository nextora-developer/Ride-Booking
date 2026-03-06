@extends('layouts.admin-app')

@section('title', '顾客')

@section('header')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">顾客</h1>
            <p class="mt-1 text-sm text-slate-500 font-semibold">管理顾客资料与查看最近活动。</p>
        </div>
    </div>
@endsection

@section('content')
    {{-- 搜索 --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.customers.index') }}"
            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative w-full sm:w-96">
                <input name="q" value="{{ $q ?? '' }}" type="text" placeholder="搜索 姓名 / 邮箱 / 电话..."
                    class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 pr-10 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                <svg class="h-5 w-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                    <circle cx="11" cy="11" r="7" />
                </svg>
            </div>

            <div class="flex items-center gap-2">
                <button
                    class="h-11 px-5 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                    搜索
                </button>

                @if (!empty($q))
                    <a href="{{ route('admin.customers.index') }}"
                        class="py-3 px-5 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                        清除
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 表格 --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="text-sm font-extrabold text-slate-900">
                顾客列表
                <span class="ml-2 text-xs font-black text-slate-500">共 {{ $customers->total() }} 位</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-black uppercase tracking-widest text-slate-400">
                        <th class="px-5 sm:px-6 py-3">顾客</th>
                        <th class="px-5 sm:px-6 py-3">全名</th>
                        <th class="px-5 sm:px-6 py-3">联系方式</th>
                        <th class="px-5 sm:px-6 py-3">挂单数额</th>
                        <th class="px-5 sm:px-6 py-3">状态</th>
                        <th class="px-5 sm:px-6 py-3">注册时间</th>
                        <th class="px-5 sm:px-6 py-3 text-right">操作</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($customers as $c)
                        <tr class="hover:bg-gray-50/60 {{ !$c->is_active ? 'opacity-60' : '' }}">

                            {{-- 顾客 --}}
                            <td class="px-5 sm:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-2xl bg-black text-white flex items-center justify-center font-extrabold">
                                        {{ strtoupper(mb_substr($c->name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-slate-900 truncate">
                                            {{ $c->name }}
                                        </div>
                                        <div class="text-xs text-slate-500 font-semibold truncate">
                                            ID：{{ $c->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 全名 --}}
                            <td class="px-5 sm:px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ $c->full_name ?? '—' }}
                                </div>
                            </td>

                            {{-- 联系方式 --}}
                            <td class="px-5 sm:px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ $c->phone ?? '—' }}
                                </div>
                                <div class="text-xs text-slate-500 font-semibold">
                                    {{ $c->email ?? '—' }}
                                </div>
                            </td>

                            {{-- 挂单数额 --}}
                            <td class="px-5 sm:px-6 py-4">

                                @php
                                    $credit = (float) ($c->credit_balance ?? 0);
                                    $isZero = $credit == 0;
                                @endphp

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold {{ $isZero ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">

                                    RM {{ $isZero ? '' : '-' }}{{ number_format($credit, 2) }}

                                </span>

                            </td>
                            {{-- 状态开关 --}}
                            <td class="px-5 sm:px-6 py-4">
                                <div class="flex flex-col items-start gap-1">
                                    <form action="{{ route('admin.customers.toggle', $c) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="group relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none
                                            {{ $c->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}
                                            hover:ring-4 {{ $c->is_active ? 'hover:ring-emerald-500/10' : 'hover:ring-rose-500/10' }}">

                                            <span
                                                class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200 ease-in-out
                                                {{ $c->is_active ? 'translate-x-5' : 'translate-x-0' }}">
                                            </span>
                                        </button>
                                    </form>

                                    <span
                                        class="text-[9px] font-black uppercase tracking-widest {{ $c->is_active ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $c->is_active ? '正常' : '已暂停' }}
                                    </span>
                                </div>
                            </td>

                            {{-- 注册时间 --}}
                            <td class="px-5 sm:px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ optional($c->created_at)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-slate-500 font-semibold">
                                    {{ optional($c->created_at)->format('h:i A') }}
                                </div>
                            </td>

                            {{-- 操作 --}}
                            <td class="px-5 sm:px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- 查看 --}}
                                    <a href="{{ route('admin.customers.show', $c) }}"
                                        class="h-9 w-9 flex items-center justify-center rounded-xl
                                                bg-white border border-gray-200 text-slate-600
                                                hover:bg-gray-50 hover:text-black transition"
                                        title="查看">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>

                                    {{-- 编辑 --}}
                                    <a href="{{ route('admin.customers.edit', $c) }}"
                                        class="h-9 w-9 flex items-center justify-center rounded-xl bg-black text-white hover:bg-slate-900 transition"
                                        title="编辑">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213 3 21l1.787-4.5L16.862 3.487z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 sm:px-6 py-10 text-center text-sm text-slate-500 font-semibold">
                                暂无顾客记录。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 sm:px-6 py-4 border-t border-gray-100">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
