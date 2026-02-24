<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' - 司机端')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900 selection:bg-indigo-100 selection:text-indigo-700">
    <div class="min-h-screen bg-[#fbfbfc] flex flex-col">

        {{-- Sticky Topbar --}}
        <header class="sticky top-0 z-50 w-full border-b border-gray-100 bg-white/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between gap-8">

                    {{-- Left: Logo --}}
                    <a href="{{ route('driver.dashboard') }}"
                        class="flex items-center gap-3 hover:opacity-90 transition-opacity">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm ring-1 ring-indigo-600/5">
                            <span class="font-extrabold text-lg tracking-tighter">D</span>
                        </div>
                        <div class="hidden sm:block leading-none">
                            <div class="text-[14px] font-bold text-slate-900 tracking-tight">Car Team</div>
                        </div>
                    </a>

                    {{-- Middle: Nav (Desktop/Tablet) --}}
                    @php
                        $isDashboard = request()->routeIs('driver.dashboard');
                        $isHistory = request()->routeIs('driver.history*');
                        $isProfile = request()->routeIs('driver.profile*');
                    @endphp

                    <nav class="hidden md:flex items-center bg-gray-100/50 p-1 rounded-2xl border border-gray-100">
                        <a href="{{ route('driver.dashboard') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isDashboard ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            仪表板
                        </a>

                        <a href="{{ route('driver.history.index') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isHistory ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            历史记录
                        </a>

                        <a href=""
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isProfile ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            个人资料
                        </a>

                        <div class="relative group">
                            <button type="button"
                                class="px-4 py-1.5 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900 transition-all inline-flex items-center gap-1">
                                更多
                                <svg class="h-3.5 w-3.5 opacity-60" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            {{-- Dropdown --}}
                            <div
                                class="absolute left-0 mt-2 w-48 rounded-2xl border border-gray-100 bg-white shadow-xl p-2 opacity-0 pointer-events-none scale-95 group-hover:opacity-100 group-hover:pointer-events-auto group-hover:scale-100 transition-all duration-200 origin-top-left">
                                <a href="#"
                                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                    客服中心
                                </a>
                                <a href="#"
                                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                    服务条款
                                </a>
                            </div>
                        </div>
                    </nav>

                    {{-- Right: User Info (Desktop) --}}
                    <div class="flex items-center gap-4">
                        <div class="hidden lg:flex flex-col items-end leading-none">
                            <span class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</span>
                        </div>

                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

                        {{-- Desktop logout --}}
                        <form method="POST" action="{{ route('logout') }}" class="hidden md:flex items-center gap-3">
                            @csrf
                            <button class="text-sm font-semibold text-slate-500 hover:text-rose-600 transition-colors">
                                退出登录
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-28 md:pb-8">

                {{-- Secondary Header (Dashboard Title etc) --}}
                @if (View::hasSection('header'))
                    <div class="mb-8">
                        @yield('header')
                    </div>
                @endif

                {{-- Notifications --}}
                <div class="space-y-4 mb-6">
                    @if (session('status'))
                        <div
                            class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm animate-in fade-in slide-in-from-top-2">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-800 text-sm">
                            <div class="flex items-center gap-2 font-bold mb-2">
                                <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                                请检查输入内容
                            </div>
                            <ul class="list-disc pl-5 space-y-0.5 opacity-90">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Content Area --}}
                <div class="relative">
                    @yield('content')
                </div>
            </div>
        </main>

        {{-- Mobile Bottom Nav (App-like) --}}
        <nav class="md:hidden fixed bottom-0 inset-x-0 z-50">
            <div class="mx-auto max-w-7xl px-4 pb-[max(env(safe-area-inset-bottom),12px)]">
                <div
                    class="rounded-3xl border border-gray-200 bg-white/85 backdrop-blur-xl shadow-[0_18px_40px_rgba(0,0,0,0.10)]">
                    <div class="grid grid-cols-4 gap-1 p-2">

                        {{-- Dashboard --}}
                        <a href="{{ route('driver.dashboard') }}"
                            class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 transition-all
                            {{ $isDashboard ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 3v18h16.5V3H3.75Zm3 3h4.5v4.5H6.75V6Zm6 0h4.5v7.5h-4.5V6ZM6.75 12h4.5v6H6.75v-6Zm6 3h4.5v3h-4.5v-3Z" />
                            </svg>
                            <span class="text-[11px] font-extrabold">首页</span>
                        </a>

                        {{-- History --}}
                        <a href="{{ route('driver.history.index') }}"
                            class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 transition-all
                            {{ $isHistory ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6l4 2M21 12a9 9 0 1 1-3.3-6.9" />
                            </svg>
                            <span class="text-[11px] font-extrabold">记录</span>
                        </a>

                        {{-- Profile --}}
                        <a href=""
                            class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 transition-all
                            {{ $isProfile ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                            </svg>
                            <span class="text-[11px] font-extrabold">资料</span>
                        </a>

                        {{-- More --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false" type="button"
                                class="w-full flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 transition-all
                                text-slate-500 hover:text-slate-900 hover:bg-slate-50">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2.25c-4.142 0-7.5 2.239-7.5 5v.75h15v-.75c0-2.761-3.358-5-7.5-5Z" />
                                </svg>
                                <span class="text-[11px] font-extrabold">更多</span>
                            </button>

                            {{-- More dropdown --}}
                            <div x-cloak x-show="open"
                                class="absolute bottom-[72px] right-0 w-56 rounded-2xl border border-gray-100 bg-white shadow-2xl overflow-hidden">
                                <div class="p-2">
                                    <div class="px-3 py-2">
                                        <div class="text-xs font-black text-slate-900 truncate">
                                            {{ auth()->user()->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-semibold capitalize">
                                            {{ auth()->user()->shift }} 班 • 司机
                                        </div>
                                    </div>

                                    <div class="h-px bg-gray-100 my-1"></div>

                                    <a href="#"
                                        class="block px-3 py-2 rounded-xl text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                        客服
                                    </a>
                                    <a href="#"
                                        class="block px-3 py-2 rounded-xl text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                        条款
                                    </a>

                                    <div class="h-px bg-gray-100 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-3 py-2 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50">
                                            退出登录
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </nav>

        {{-- Footer (hide on mobile to feel like app) --}}
        <footer class="hidden md:block mt-auto border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-2">
                        <div
                            class="h-6 w-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] font-bold text-white">
                            D</div>
                        <p class="text-[13px] text-slate-500 font-medium">
                            © {{ date('Y') }} {{ config('app.name') }}. 司机端。
                        </p>
                    </div>

                    <div class="flex items-center gap-8 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <a href="#" class="hover:text-slate-900 transition-colors">隐私</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">指南</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">客服</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>
</body>

</html>