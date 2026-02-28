<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 py-12 bg-slate-50/50">
        <div class="w-full max-w-sm">

            <div
                class="bg-white rounded-[3rem] shadow-[0_24px_70px_rgba(15,23,42,0.08)] border border-slate-200/60 overflow-hidden relative">

                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50">
                </div>

                <div class="p-10">
                    {{-- Header --}}
                    <div class="text-center mb-10">
                        <div class="inline-flex relative mb-6">
                            <div class="absolute inset-0 bg-indigo-500 blur-2xl opacity-20 rounded-full"></div>
                            <div
                                class="relative h-20 w-20 rounded-[2rem] bg-slate-900 flex items-center justify-center shadow-xl shadow-slate-200">
                                <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </div>
                        </div>

                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">司机登录</h1>
                        <p class="text-[13px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                            登录司机仪表板
                        </p>
                    </div>

                    {{-- Errors (optional) --}}
                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
                            <div class="text-xs font-black text-rose-700 uppercase tracking-widest mb-1">
                                登录失败
                            </div>
                            <ul class="text-sm font-semibold text-rose-700 list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('driver.login.store') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label for="email"
                                class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                电子邮箱
                            </label>

                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 2.25 17.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75m19.5 0l-9.75 6.75L2.25 6.75" />
                                    </svg>
                                </div>

                                <input id="email" name="email" type="email" required autofocus
                                    autocomplete="username" value="{{ old('email') }}" placeholder="driver@email.com"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent
                                           focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50
                                           transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="space-y-2">
                            <div class="flex items-center justify-between px-1">
                                <label for="password"
                                    class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em]">
                                    密码
                                </label>
                            </div>

                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z" />
                                    </svg>
                                </div>

                                <input id="password" name="password" type="password" required
                                    autocomplete="current-password" placeholder="••••••••"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent
                                           focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50
                                           transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="group w-full relative mt-4 bg-slate-900 text-white rounded-2xl p-1
                                   shadow-[0_10px_30px_rgba(15,23,42,0.2)] hover:shadow-[0_15px_35px_rgba(15,23,42,0.3)]
                                   active:scale-[0.98] transition-all duration-200">
                            <div class="relative py-4 px-6 flex items-center justify-center gap-3">
                                <span class="text-sm font-black uppercase tracking-widest">登录司机端</span>
                                <svg class="h-5 w-5 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12l-7.5 7.5M3 12h18" />
                                </svg>
                            </div>
                        </button>
                    </form>

                    {{-- Footer --}}
                    <div class="mt-8 text-center space-y-4">
                        <p class="text-sm font-bold text-slate-500">
                            还没有司机账号？
                            <a href="{{ route('driver.register') }}"
                                class="text-indigo-600 hover:text-indigo-800 transition">
                                注册司机账号
                            </a>
                        </p>

                        {{-- <a href="{{ route('login') }}" class="text-[11px] font-black text-slate-400 hover:text-slate-600 transition uppercase tracking-widest">
                            返回顾客登录
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>