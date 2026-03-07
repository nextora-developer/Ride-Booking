<header x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="scrolled ? 'bg-white/80 border-slate-200/60' : 'bg-white border-transparent'"
    class="sticky top-0 z-50 backdrop-blur-md transition-all duration-300 border-b">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between">

            <div class="flex items-center">
                <a href="/" class="group">
                    <img src="{{ asset('images/exdrive-logo.png') }}" alt="Ride Booking System"
                        class="h-12 w-auto transform group-hover:scale-105 transition-transform duration-300">
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-10">
                @foreach ([['url' => '/#intro', 'label' => '系统介绍'], ['url' => '/#scenes', 'label' => '适用场景'], ['url' => '/#features', 'label' => '功能特色'], ['url' => '/#roles', 'label' => '角色说明']] as $item)
                    <a href="{{ $item['url'] }}"
                        class="relative text-sm font-bold text-slate-600 hover:text-sky-600 transition-colors py-2 group">
                        {{ $item['label'] }}
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-sky-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        :class="open ? 'ring-4 ring-sky-500/10 bg-sky-600' : 'bg-sky-500 hover:bg-sky-600'"
                        class="inline-flex items-center justify-center rounded-xl px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-sky-500/25 transition-all active:scale-95">
                        立即体验
                        <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : ''"
                            class="ml-2 h-4 w-4 transition-transform duration-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2" @click.outside="open=false"
                        class="absolute right-0 mt-3 w-64 rounded-2xl bg-white shadow-2xl shadow-slate-200/50 border border-slate-100 p-2 overflow-hidden"
                        style="display: none;">

                        <div class="px-3 py-2 mb-1">
                            <span class="text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">选择登录入口</span>
                        </div>

                        <a href="{{ route('login') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-sky-50 transition-all">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-600 group-hover:bg-sky-500 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-slate-700">顾客入口</span>
                        </a>

                        <a href="{{ route('driver.login') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 transition-all">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-slate-700">司机入口</span>
                        </a>

                        <a href="{{ route('manager.login') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-amber-50 transition-all">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-slate-700">经理入口</span>
                        </a>

                        <div class="my-1 border-t border-slate-100"></div>

                        <a href="{{ route('admin.login') }}"
                            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-100 transition-all">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-200 text-slate-600 group-hover:bg-slate-700 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-slate-700">管理后台</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>