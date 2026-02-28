<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 py-12 bg-slate-50/50">
        <div class="w-full max-w-sm">

            {{-- Main Login Card --}}
            <div
                class="bg-white rounded-[3rem] shadow-[0_24px_70px_rgba(0,0,0,0.35)] border border-slate-200/60 overflow-hidden relative">

                {{-- Top Decorative Line --}}
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-blue-600 to-transparent opacity-70">
                </div>

                <div class="p-10">

                    {{-- Logo & Title --}}
                    <div class="text-center mb-10">
                        <div class="inline-flex relative mb-6">
                            <div class="absolute inset-0 bg-blue-600 blur-2xl opacity-20 rounded-full"></div>
                            <div
                                class="relative h-20 w-20 rounded-[2rem] bg-slate-900 flex items-center justify-center shadow-xl shadow-slate-400/30">
                                <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0Z" />
                                </svg>
                            </div>
                        </div>

                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                            Admin Access
                        </h1>
                        <p class="text-[13px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                            Restricted Control Panel
                        </p>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                Admin Email
                            </label>

                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                     <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 2.25 17.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75m19.5 0l-9.75 6.75L2.25 6.75" />
                                    </svg>
                                </div>

                                <input type="email" name="email" required
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-blue-100 focus:bg-white focus:ring-4 focus:ring-blue-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300"
                                    placeholder="admin@company.com" />
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                Password
                            </label>

                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z" />
                                    </svg>
                                </div>

                                <input type="password" name="password" required
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-blue-100 focus:bg-white focus:ring-4 focus:ring-blue-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300"
                                    placeholder="••••••••" />
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="group w-full relative mt-4 bg-slate-900 text-white rounded-2xl p-1 shadow-[0_10px_30px_rgba(0,0,0,0.3)] hover:shadow-[0_15px_35px_rgba(0,0,0,0.4)] active:scale-[0.98] transition-all duration-200">
                            <div class="relative py-4 px-6 flex items-center justify-center gap-3">
                                <span class="text-sm font-black uppercase tracking-widest">
                                    Login as Admin
                                </span>
                                <svg class="h-5 w-5 transform group-hover:translate-x-1 transition-transform"
                                     fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M13.5 4.5 21 12l-7.5 7.5M3 12h18" />
                                </svg>
                            </div>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>