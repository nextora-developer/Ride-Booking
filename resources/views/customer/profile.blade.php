@extends('layouts.customer-app')

@section('title', '个人资料')

@section('header')
    <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">
        <div class="absolute left-0 top-1/2 -translate-y-1/2">
            <a href="{{ route('customer.home') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>

        <div class="text-center">
            <h1 class="text-lg font-black text-slate-800 leading-none">个人资料</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">账户信息</p>
        </div>
    </div>
@endsection

@section('content')
    @php
        $u = auth()->user();
        $initial = strtoupper(substr($u->name ?? 'U', 0, 1));
    @endphp

    <div class="pb-24 space-y-6">

    {{-- Profile Summary --}}
    <div
        class="bg-white rounded-[2.5rem] p-6 md:p-8
               shadow-[0_14px_34px_rgba(15,23,42,0.08)]
               border border-slate-200">
        <div class="flex items-center gap-5">
            <div
                class="h-16 w-16 rounded-[2rem] bg-slate-900 text-white flex items-center justify-center font-black text-2xl
                       shadow-[0_18px_45px_rgba(15,23,42,0.18)]">
                {{ $initial }}
            </div>

            <div class="min-w-0 flex-1">
                <div class="text-xl font-black text-slate-900 truncate">{{ $u->name }}</div>
                <div class="text-sm font-bold text-slate-600 truncate">{{ $u->email }}</div>

                <div
                    class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200/70 text-slate-800 text-[11px] font-black uppercase tracking-widest border border-slate-300/70">
                    顾客
                </div>
            </div>
        </div>
    </div>

    {{-- Editable Fields --}}
    <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- 基本资料 --}}
            <div
                class="bg-white rounded-[2.5rem] p-6
                       shadow-[0_12px_28px_rgba(15,23,42,0.08)]
                       border border-slate-200">
                <div class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-4">
                    基本资料
                </div>

                <label class="block text-[11px] font-black text-slate-700 uppercase tracking-[0.15em] ml-1 mb-2">
                    用户名
                </label>

                <div class="group relative">
                    <div
                        class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-700 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0" />
                        </svg>
                    </div>

                    <input name="name" value="{{ old('name', $u->name) }}" required
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/85 border border-slate-300/80
                               focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/10
                               transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-500
                               shadow-[0_14px_34px_rgba(15,23,42,0.10)]" />
                </div>

                @error('name')
                    <p class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</p>
                @enderror

                <div class="mt-6">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-[0.15em] ml-1 mb-2">
                        真实姓名
                    </label>

                    <div class="group relative">
                        <div
                            class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-700 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0" />
                            </svg>
                        </div>

                        <input name="full_name" value="{{ old('full_name', $u->full_name) }}"
                            placeholder="与身份证一致的姓名"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/85 border border-slate-300/80
                                   focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/10
                                   transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-500
                                   shadow-[0_14px_34px_rgba(15,23,42,0.10)]" />
                    </div>

                    @error('full_name')
                        <p class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 联系方式 --}}
            <div
                class="bg-white rounded-[2.5rem] p-6
                       shadow-[0_12px_28px_rgba(15,23,42,0.08)]
                       border border-slate-200">
                <div class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-4">
                    联系方式
                </div>

                <label class="block text-[11px] font-black text-slate-700 uppercase tracking-[0.15em] ml-1 mb-2">
                    手机号码
                </label>

                <div class="group relative">
                    <div
                        class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-700 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </div>

                    <input name="phone" value="{{ old('phone', $u->phone) }}" placeholder="012-3456789"
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/85 border border-slate-300/80
                               focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/10
                               transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-500
                               shadow-[0_14px_34px_rgba(15,23,42,0.10)]" />
                </div>

                @error('phone')
                    <p class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</p>
                @enderror

                <div class="mt-6">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-[0.15em] ml-1 mb-2">
                        身份证号码
                    </label>

                    <div class="group relative">
                        <div
                            class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-700 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 7.5H9m6 3H9m10.5 9H4.5A2.25 2.25 0 012.25 17.25V6.75A2.25 2.25 0 014.5 4.5h15A2.25 2.25 0 0121.75 6.75v10.5A2.25 2.25 0 0119.5 19.5Z" />
                            </svg>
                        </div>

                        <input name="ic_number" value="{{ old('ic_number', $u->ic_number) }}" placeholder="身份证 / NRIC"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/85 border border-slate-300/80
                                   focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/10
                                   transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-500
                                   shadow-[0_14px_34px_rgba(15,23,42,0.10)]" />
                    </div>

                    @error('ic_number')
                        <p class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <div class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">
                        邮箱
                    </div>

                    <div
                        class="rounded-2xl bg-slate-100/70 border border-slate-300/70 px-4 py-4
                               text-sm font-black text-slate-800 shadow-[0_10px_24px_rgba(15,23,42,0.06)]">
                        {{ $u->email }}
                        <div class="text-[11px] font-bold text-slate-600 mt-1">邮箱需由系统管理员协助更换</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security --}}
        <div class="bg-slate-900 rounded-[2.5rem] p-6 text-white shadow-[0_22px_60px_rgba(15,23,42,0.25)]">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-black text-slate-300 uppercase tracking-widest">安全设置</div>
                    <div class="text-lg font-black mt-1">账号安全</div>
                    <div class="text-sm font-bold text-slate-300 mt-1">建议定期更换密码，保障账户安全</div>
                </div>

                <a href="{{ route('customer.password.edit') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-sm font-black transition">
                    更换密码
                </a>
            </div>
        </div>

        {{-- Save --}}
        <div class="flex flex-col md:flex-row gap-3 justify-end">
            <a href="{{ route('customer.home') }}"
                class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-white border border-slate-300/70
                       text-slate-900 font-black hover:bg-slate-50 transition shadow-[0_10px_24px_rgba(15,23,42,0.06)]">
                取消
            </a>

            <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white font-black
                       shadow-[0_18px_45px_rgba(15,23,42,0.22)]
                       hover:bg-slate-800 active:scale-[0.98] transition">
                保存更改
            </button>
        </div>

    </form>
</div>
@endsection
