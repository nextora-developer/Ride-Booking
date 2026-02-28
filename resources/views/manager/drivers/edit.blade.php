@extends('layouts.manager-app')

@section('title', '编辑司机')

@section('header')
    <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">
        <div class="absolute left-0 top-1/2 -translate-y-1/2">
            <a href="{{ route('manager.drivers.index') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>

        <div class="text-center">
            <h1 class="text-lg font-black text-slate-800 leading-none">编辑资料</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Edit Profile</p>
        </div>

        {{-- Avatar Right --}}
        <div class="absolute right-4 top-1/2 -translate-y-1/2">
            <div
                class="h-10 w-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-sm shadow-lg shadow-slate-200">
                {{ strtoupper(substr($driver->name, 0, 1)) }}
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto pb-7 px-2">
        <form method="POST" action="{{ route('manager.drivers.update', $driver) }}" class="mt-6 space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Basic Info Group --}}
                <div
                    class="bg-white rounded-[2.5rem] p-8 border border-slate-200 shadow-[0_14px_34px_rgba(15,23,42,0.08)] space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xl">👤</span>
                        <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">基础资料</h3>
                    </div>

                    <div class="space-y-4">

                        <div class="group">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1">
                                用户名
                            </label>
                            <input name="name" value="{{ old('name', $driver->name) }}" readonly
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-100 px-5 py-4 text-sm font-bold text-slate-600 cursor-not-allowed" />
                        </div>

                        <div class="group">
                            <label
                                class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1 group-focus-within:text-indigo-600 transition-colors">
                                真实姓名 (Full Name)
                            </label>
                            <input name="full_name" value="{{ old('full_name', $driver->full_name) }}"
                                placeholder="请输入司机真实姓名"
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-5 py-4 text-sm font-bold text-slate-900
                                       focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10
                                       transition-all placeholder:text-slate-400" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1">所属班次</label>
                                <select name="shift"
                                    class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-5 py-4 text-sm font-bold text-slate-900
                                           focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                                    <option value="">-</option>
                                    <option value="day" @selected(old('shift', $driver->shift) === 'day')>Day Shift</option>
                                    <option value="night" @selected(old('shift', $driver->shift) === 'night')>Night Shift</option>
                                </select>
                            </div>

                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1">审核状态</label>
                                <select name="driver_status"
                                    class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-5 py-4 text-sm font-bold text-slate-900
                                           focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                                    @foreach (['pending', 'approved', 'rejected', 'inactive'] as $st)
                                        <option value="{{ $st }}" @selected(old('driver_status', $driver->driver_status) === $st)>
                                            {{ ucfirst($st) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="group">
                            <label
                                class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1 group-focus-within:text-indigo-600 transition-colors">
                                联系电话
                            </label>
                            <input name="phone" value="{{ old('phone', $driver->phone) }}" placeholder="例如 012-3456789"
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-5 py-4 text-sm font-bold text-slate-900
                                       focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400" />
                        </div>

                        <div class="group">
                            <label
                                class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1 group-focus-within:text-indigo-600 transition-colors">
                                电子邮箱
                            </label>
                            <input type="email" name="email" value="{{ old('email', $driver->email) }}"
                                placeholder="例如 driver@email.com"
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-5 py-4 text-sm font-bold text-slate-900
                                       focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10
                                       transition-all placeholder:text-slate-400" />
                        </div>
                    </div>
                </div>

                {{-- Vehicle Info Group --}}
                <div
                    class="bg-white rounded-[2.5rem] p-8 border border-slate-200 shadow-[0_14px_34px_rgba(15,23,42,0.08)] space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xl">🚗</span>
                        <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">车辆/证件</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="group">
                            <label
                                class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1 group-focus-within:text-indigo-600 transition-colors">
                                IC 身份证号
                            </label>
                            <input name="ic_number" value="{{ old('ic_number', $driver->ic_number) }}"
                                placeholder="例如 900101-10-1234"
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-5 py-4 text-sm font-bold text-slate-900
                                       focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1 group-focus-within:text-indigo-600 transition-colors">
                                    车牌号码
                                </label>
                                <input name="car_plate" value="{{ old('car_plate', $driver->car_plate) }}"
                                    class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-5 py-4 text-sm font-bold text-slate-900
                                           focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400"
                                    placeholder="JXX 1234" />
                            </div>

                            <div class="group">
                                <label
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-[0.1em] ml-1 group-focus-within:text-indigo-600 transition-colors">
                                    车辆型号
                                </label>
                                <input name="car_model" value="{{ old('car_model', $driver->car_model) }}"
                                    class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50/60 px-5 py-4 text-sm font-bold text-slate-900
                                           focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400"
                                    placeholder="Toyota Vios" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bank Info Group (Span 2) --}}
                <div
                    class="lg:col-span-2 bg-slate-100/70 rounded-[2.5rem] p-8 border border-slate-200 shadow-[0_14px_34px_rgba(15,23,42,0.06)] space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xl">💳</span>
                        <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">结算银行账户</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-600 uppercase tracking-[0.1em] ml-1">
                                银行名称
                            </label>
                            <input name="bank_name" value="{{ old('bank_name', $driver->bank_name) }}"
                                placeholder="例如 Maybank / CIMB / Public Bank"
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-bold text-slate-900
                                       focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400" />
                        </div>

                        <div class="group">
                            <label class="text-[10px] font-black text-slate-600 uppercase tracking-[0.1em] ml-1">
                                银行账号
                            </label>
                            <input name="bank_account" value="{{ old('bank_account', $driver->bank_account) }}"
                                placeholder="请输入银行账户号码"
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-bold text-slate-900
                                       focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Actions --}}
            <div class="bottom-6 left-0 right-0 px-6 z-40 lg:relative lg:bottom-0 lg:px-0">
                <div
                    class="bg-white/85 backdrop-blur-xl p-4 rounded-3xl border border-slate-200 shadow-[0_22px_60px_rgba(15,23,42,0.18)]
                           lg:shadow-none lg:bg-transparent lg:border-none lg:p-0 flex items-center gap-3">
                    <button
                        class="flex-1 h-14 rounded-2xl bg-slate-900 text-white font-black tracking-widest text-sm
                               hover:bg-indigo-600 active:scale-95 transition-all">
                        更新档案资料
                    </button>

                    <a href="{{ route('manager.drivers.index') }}"
                        class="px-8 h-14 rounded-2xl bg-white border border-slate-200 text-slate-600 font-black text-sm
                               flex items-center justify-center hover:bg-slate-50 transition-all">
                        取消
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
