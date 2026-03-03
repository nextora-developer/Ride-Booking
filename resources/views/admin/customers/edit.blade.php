@extends('layouts.admin-app')

@section('title', '编辑顾客')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                编辑顾客
            </h1>
            <p class="mt-1 text-sm text-slate-500 font-semibold">
                更新顾客资料与信息。
            </p>
        </div>

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

    @if (session('success'))
        <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-4">
            <div class="text-sm font-extrabold text-emerald-900">
                ✅ {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 px-6 py-4">
            <div class="text-sm font-extrabold text-rose-900">
                ❌ {{ $errors->first() }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- 左侧：主要表单 --}}
        <div class="lg:col-span-2">
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6 sm:p-8">

                <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                    @csrf
                    @method('PATCH')

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">基本资料</div>
                                <div class="text-xs text-slate-500 font-semibold mt-1">顾客个人资料与联系方式。</div>
                            </div>
                            <span class="text-xs font-black text-slate-400">ID：{{ $customer->id }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- 用户名 / 显示名称 --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    用户名 / 显示名称
                                </label>
                                <input type="text" name="name" value="{{ old('name', $customer->name) }}"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('name')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 全名 --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    全名
                                </label>
                                <input type="text" name="full_name" value="{{ old('full_name', $customer->full_name) }}"
                                    placeholder="例如：Tan Ah Kow"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('full_name')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 电话 --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    电话号码
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('phone')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 邮箱 --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    邮箱
                                </label>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('email')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 身份证号码 --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    身份证号码（IC）
                                </label>
                                <input type="text" name="ic_number" value="{{ old('ic_number', $customer->ic_number) }}"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('ic_number')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 启用状态 --}}
                    <div class="my-5 rounded-2xl bg-gray-50 border border-gray-100 p-4 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-extrabold text-slate-900">账号状态</div>
                            <div class="text-xs text-slate-500 font-semibold mt-1">
                                关闭账号后将无法登录与下单。
                            </div>
                        </div>

                        <label class="inline-flex items-center gap-3">
                            <span
                                class="text-xs font-black {{ old('is_active', $customer->is_active) ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ old('is_active', $customer->is_active) ? '启用' : '停用' }}
                            </span>

                            <input type="checkbox" name="is_active" value="1"
                                class="h-5 w-5 rounded border-gray-300 text-black focus:ring-black"
                                @checked(old('is_active', $customer->is_active))>
                        </label>
                    </div>

                    {{-- 按钮 --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="h-11 px-6 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                            保存
                        </button>

                        <a href="{{ route('admin.customers.show', $customer) }}"
                            class="h-11 px-6 inline-flex items-center justify-center rounded-2xl bg-white border border-gray-200
                                       text-sm font-extrabold hover:bg-gray-50 transition">
                            取消
                        </a>
                    </div>

                </form>

            </div>
        </div>

        {{-- 右侧：信息卡片 --}}
        <div class="space-y-6">

            {{-- 信用额概览 --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6">

                <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                    挂单数额
                </div>

                @php
                    $balance = (float) ($customer->credit_balance ?? 0);
                @endphp

                <div class="mt-4 text-center">
                    <div
                        class="text-3xl font-extrabold
            {{ $balance > 0 ? 'text-emerald-600' : ($balance < 0 ? 'text-rose-600' : 'text-slate-900') }}">
                        RM {{ number_format($balance, 2) }}
                    </div>

                    <div
                        class="mt-2 text-xs font-black uppercase tracking-widest
            {{ $balance > 0 ? 'text-emerald-600' : ($balance < 0 ? 'text-rose-600' : 'text-slate-400') }}">

                        @if ($balance > 0)
                            顾客尚有余额
                        @elseif ($balance < 0)
                            顾客欠款
                        @else
                            暂无欠款/余额
                        @endif

                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3 text-center text-xs font-semibold text-slate-500">
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 py-3">
                        顾客ID
                        <div class="mt-1 font-extrabold text-slate-900">
                            {{ $customer->id }}
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gray-50 border border-gray-100 py-3">
                        状态
                        <div class="mt-1 font-extrabold {{ $customer->is_active ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $customer->is_active ? '启用' : '停用' }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- 调整信用额 --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6">

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-extrabold text-slate-900">调整信用额</div>
                        <div class="text-xs text-slate-500 font-semibold mt-1">
                            增加 / 扣除，或清零。
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">余额</div>
                        <div class="text-lg font-extrabold text-slate-900">
                            RM {{ number_format($customer->credit_balance ?? 0, 2) }}
                        </div>
                    </div>
                </div>

                <div class="mt-5 space-y-3">

                    {{-- 增加 --}}
                    <form action="{{ route('admin.customers.credit.adjust', $customer) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="action" value="add">

                        <div class="flex gap-2">
                            <input type="number" step="0.01" min="0.01" name="amount" required placeholder="金额"
                                class="flex-1 h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                       focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition">

                            <button type="submit"
                                class="h-11 px-4 rounded-2xl bg-emerald-600 text-white text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition">
                                增加
                            </button>
                        </div>
                    </form>

                    {{-- 扣除 --}}
                    <form action="{{ route('admin.customers.credit.adjust', $customer) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="action" value="deduct">

                        <div class="flex gap-2">
                            <input type="number" step="0.01" min="0.01" name="amount" required
                                placeholder="金额"
                                class="flex-1 h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                       focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition">

                            <button type="submit"
                                class="h-11 px-4 rounded-2xl bg-rose-600 text-white text-xs font-black uppercase tracking-widest hover:bg-rose-700 transition">
                                扣除
                            </button>
                        </div>
                    </form>

                    {{-- 清零 --}}
                    <form action="{{ route('admin.customers.credit.clear', $customer) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="w-full h-11 rounded-2xl bg-gray-100 text-gray-700 text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition">
                            清零（设为 0）
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
