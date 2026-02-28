<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 py-12 bg-slate-50/50">
        <div class="w-full max-w-sm">

            {{-- Main Register Card --}}
            <div
                class="bg-white rounded-[3rem] shadow-[0_24px_70px_rgba(15,23,42,0.08)] border border-slate-200/60 overflow-hidden relative">

                {{-- Decorative top line --}}
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50">
                </div>

                <div class="p-10">

                    {{-- Logo & Welcome --}}
                    <div class="text-center mb-10">
                        <div class="inline-flex relative mb-6">
                            <div class="absolute inset-0 bg-indigo-500 blur-2xl opacity-20 rounded-full"></div>
                            <div
                                class="relative h-20 w-20 rounded-[2rem] bg-slate-900 flex items-center justify-center shadow-xl shadow-slate-200">
                                <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                        </div>

                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">注册账号</h1>
                        <p class="text-[13px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                            填写资料开始使用
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        {{-- Name --}}
                        <div class="space-y-2">
                            <label for="full_name"
                                class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                姓名
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                                    </svg>
                                </div>
                                <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}"
                                    required autofocus placeholder="例如：Sia Kai Fong"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent
                                           focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50
                                           transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            <x-input-error :messages="$errors->get('full_name')" class="mt-1 ml-1" />
                        </div>

                        {{-- Phone --}}
                        <div class="space-y-2">
                            <label for="phone"
                                class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                手机号码
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                    </svg>
                                </div>

                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                                    inputmode="tel" placeholder="例如：0123456789"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent
                                            focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50
                                            transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-1 ml-1" />
                        </div>

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
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                    placeholder="name@company.com"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent
                                           focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50
                                           transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 ml-1" />
                        </div>

                        {{-- Password --}}
                        <div class="space-y-2">
                            <label for="password"
                                class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                密码
                            </label>
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
                                    autocomplete="new-password" placeholder="••••••••"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent
                                           focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50
                                           transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 ml-1" />
                        </div>

                        {{-- Confirm Password --}}
                        <div class="space-y-2">
                            <label for="password_confirmation"
                                class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                确认密码
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z" />
                                    </svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    required autocomplete="new-password" placeholder="••••••••"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent
                                           focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50
                                           transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 ml-1" />
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="group w-full relative mt-2 bg-slate-900 text-white rounded-2xl p-1
                                   shadow-[0_10px_30px_rgba(15,23,42,0.2)] hover:shadow-[0_15px_35px_rgba(15,23,42,0.3)]
                                   active:scale-[0.98] transition-all duration-200">
                            <div class="relative py-4 px-6 flex items-center justify-center gap-3">
                                <span class="text-sm font-black uppercase tracking-widest">注册</span>
                                <svg class="h-5 w-5 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12l-7.5 7.5M3 12h18" />
                                </svg>
                            </div>
                        </button>
                    </form>

                    {{-- Footer Link --}}
                    <div class="mt-8 text-center space-y-4">
                        <p class="text-sm font-bold text-slate-500">
                            已有账号？
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 transition">登录</a>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-guest-layout>