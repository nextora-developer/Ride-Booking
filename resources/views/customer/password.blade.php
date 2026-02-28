@extends('layouts.customer-app')

@section('title', '更换密码')

@section('header')
    <div class="relative px-2">
        {{-- Mobile Header --}}
        <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">
            <div class="absolute left-0 top-1/2 -translate-y-1/2">
                <a href="{{ route('customer.profile') }}"
                    class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </div>

            <div class="text-center">
                <h1 class="text-lg font-black text-slate-800 leading-none">更换密码</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Change Password</p>
            </div>
        </div>

        {{-- Desktop Header --}}
        <div class="hidden md:flex items-end justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">更换密码</h1>
                <p class="text-slate-500 font-medium mt-1">请输入当前密码以验证身份</p>
            </div>
            <a href="{{ route('customer.profile') }}"
                class="px-6 py-2.5 rounded-2xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                返回资料
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="pb-24 space-y-6">

        {{-- Tip Card --}}
        <div
            class="bg-white rounded-[2.5rem] p-6
                   shadow-[0_14px_34px_rgba(15,23,42,0.08)]
                   border border-slate-200">
            <div class="flex items-start gap-4">
                <div
                    class="h-12 w-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center
                           shadow-[0_18px_45px_rgba(15,23,42,0.18)]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5Z" />
                    </svg>
                </div>

                <div class="flex-1">
                    <div class="text-sm font-black text-slate-900">账号安全建议</div>
                    <div class="text-sm font-semibold text-slate-600 mt-1 leading-relaxed">
                        • 建议使用<strong class="text-slate-900">至少 8 个字符</strong>，并包含数字与大小写字母。<br>
                        • 不要重复使用旧密码或与其他平台相同的密码。
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div
            class="bg-white rounded-[2.5rem] p-6 md:p-8
                   shadow-[0_14px_34px_rgba(15,23,42,0.08)]
                   border border-slate-200">
            <form method="POST" action="{{ route('customer.password.update') }}" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Current Password --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-[0.15em] ml-1">
                        当前密码
                    </label>

                    <div class="group relative">
                        <div
                            class="absolute inset-y-0 left-4 flex items-center pointer-events-none
                                   text-slate-500 group-focus-within:text-indigo-700 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5Z" />
                            </svg>
                        </div>

                        <input name="current_password" type="password" required placeholder="••••••••"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl
                                   bg-white/85 border border-slate-300/80
                                   focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/10
                                   transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-500
                                   shadow-[0_14px_34px_rgba(15,23,42,0.10)]" />
                    </div>

                    @error('current_password')
                        <p class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-[0.15em] ml-1">
                        新密码
                    </label>

                    <div class="group relative">
                        <div
                            class="absolute inset-y-0 left-4 flex items-center pointer-events-none
                                   text-slate-500 group-focus-within:text-indigo-700 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5Z" />
                            </svg>
                        </div>

                        <input name="password" type="password" required placeholder="至少 8 个字符"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl
                                   bg-white/85 border border-slate-300/80
                                   focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/10
                                   transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-500
                                   shadow-[0_14px_34px_rgba(15,23,42,0.10)]" />
                    </div>

                    @error('password')
                        <p class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-[0.15em] ml-1">
                        确认新密码
                    </label>

                    <div class="group relative">
                        <div
                            class="absolute inset-y-0 left-4 flex items-center pointer-events-none
                                   text-slate-500 group-focus-within:text-indigo-700 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5Z" />
                            </svg>
                        </div>

                        <input name="password_confirmation" type="password" required placeholder="••••••••"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl
                                   bg-white/85 border border-slate-300/80
                                   focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/10
                                   transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-500
                                   shadow-[0_14px_34px_rgba(15,23,42,0.10)]" />
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col md:flex-row gap-3 justify-end pt-2">
                    <a href="{{ route('customer.profile') }}"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-2xl
                               bg-white border border-slate-300/70 text-slate-900 font-black
                               hover:bg-slate-50 transition shadow-[0_10px_24px_rgba(15,23,42,0.06)]">
                        取消
                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl
                               bg-slate-900 text-white font-black
                               shadow-[0_18px_45px_rgba(15,23,42,0.22)]
                               hover:bg-slate-800 active:scale-[0.98] transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" />
                        </svg>
                        更新密码
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
