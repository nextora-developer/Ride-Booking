<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' - 顾客端')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-900 selection:bg-pink-100 selection:text-pink-700">
    <div class="min-h-screen bg-[#fbfbfc] flex flex-col">

        {{-- Sticky Topbar --}}
        {{-- <header class="sticky top-0 z-50 w-full border-b border-gray-100 bg-white/80 backdrop-blur-md"> --}}
        <header class="hidden md:block sticky top-0 z-50 w-full border-b border-gray-100 bg-white/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between gap-8">

                    {{-- Brand / Logo --}}
                    <div class="flex-1 md:flex-none">
                        {{-- Mobile: center photo logo --}}
                        <a href="{{ route('customer.home') }}"
                            class="md:hidden flex items-center justify-center gap-3 hover:opacity-90 transition-opacity">
                            <img src="{{ asset('images/extechstudio-black-logo.png') }}" alt="Car Team" class="">
                        </a>

                        {{-- Desktop: left logo (keep your original design) --}}
                        <a href="{{ route('customer.home') }}"
                            class="hidden md:flex items-center gap-3 hover:opacity-90 transition-opacity">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm ring-1 ring-slate-900/5">
                                <span class="font-extrabold text-lg tracking-tighter">C</span>
                            </div>
                            <div class="hidden sm:block leading-none">
                                <div class="text-[14px] font-bold text-slate-900 tracking-tight">Car Team</div>
                            </div>
                        </a>
                    </div>

                    {{-- Middle: Nav (Desktop/Tablet) --}}
                    @php
                        $isHome = request()->routeIs('customer.home');
                        $isBook = request()->routeIs('customer.book');
                        $isOrder = request()->routeIs('customer.orders.*');
                        $isProfile =
                            request()->routeIs('customer.profile*') || request()->routeIs('customer.password*');
                    @endphp

                    <nav class="hidden md:flex items-center bg-gray-100/50 p-1 rounded-2xl border border-gray-100">
                        <a href="{{ route('customer.home') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isHome ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            首页
                        </a>
                        <a href="{{ route('customer.book') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isBook ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            预约下单
                        </a>
                        <a href="{{ route('customer.orders.index') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                    {{ $isOrder ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            我的订单
                        </a>

                        <div class="relative group">
                            <button type="button"
                                class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all inline-flex items-center gap-1
                                        {{ $isProfile ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
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
                                <a href="{{ route('customer.profile') }}"
                                    class="block px-3 py-2 rounded-lg text-sm transition-colors
                                            {{ $isProfile
                                            ? 'bg-slate-100 text-slate-900 font-semibold'
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                    个人资料
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
            {{-- Blur background behind the nav area for better readability of content underneath --}}
            <div
                class="absolute inset-0 bg-gradient-to-t from-slate-100/80 to-transparent backdrop-blur-sm -z-10 h-24 mt-auto">
            </div>

            <div class="mx-auto max-w-7xl px-4 pb-[max(env(safe-area-inset-bottom),16px)]">
                <div
                    class="relative rounded-[2.5rem] border border-white/50 bg-white/90 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.12)] ring-1 ring-slate-900/5">
                    <div class="grid grid-cols-5 gap-1 p-2 items-center">

                        @php
                            $isHome = $isHome ?? false;
                            $isOrder = $isOrder ?? false;
                            $isBook = $isBook ?? false;
                            $isProfile = $isProfile ?? false;
                            $isMore = $isMore ?? false;

                            $activeClass = 'text-slate-900 scale-110';
                            $inactiveClass = 'text-slate-400 hover:text-slate-600';
                        @endphp

                        {{-- Home --}}
                        <a href="{{ route('customer.home') }}"
                            class="flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $isHome ? $activeClass : $inactiveClass }}">
                            <svg class="h-6 w-6" fill="{{ $isHome ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75V20.25A1.5 1.5 0 006 21.75h3.75v-6a1.5 1.5 0 011.5-1.5h1.5a1.5 1.5 0 011.5 1.5v6H18a1.5 1.5 0 001.5-1.5V9.75" />
                            </svg>
                            <span
                                class="text-[10px] font-black mt-1 tracking-tight {{ $isHome ? 'opacity-100' : 'opacity-70' }}">首页</span>
                            @if ($isHome)
                                <div class="h-1 w-1 rounded-full bg-slate-900 mt-0.5"></div>
                            @endif
                        </a>

                        {{-- Orders --}}
                        <a href="{{ route('customer.orders.index') }}"
                            class="flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $isOrder ? $activeClass : $inactiveClass }}">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6l4 2M21 12a9 9 0 11-3.3-6.9" />
                            </svg>
                            <span
                                class="text-[10px] font-black mt-1 tracking-tight {{ $isOrder ? 'opacity-100' : 'opacity-70' }}">订单</span>
                            @if ($isOrder)
                                <div class="h-1 w-1 rounded-full bg-slate-900 mt-0.5"></div>
                            @endif
                        </a>

                        {{-- Book (Elevated Center) --}}
                        <div class="relative -mt-8 flex justify-center">
                            <div class="absolute inset-0 bg-slate-900/20 blur-2xl rounded-full scale-75"></div>
                            <a href="{{ route('customer.book') }}"
                                class="relative h-16 w-16 rounded-[2rem] bg-slate-900 text-white flex items-center justify-center shadow-[0_15px_30px_rgba(15,23,42,0.35)] active:scale-90 transition-all duration-200 ring-4 ring-white">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M4 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0m10 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM4.862 4.276 3.906 6.19a.51.51 0 0 0 .497.731c.91-.073 2.35-.17 3.597-.17s2.688.097 3.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 10.691 4H5.309a.5.5 0 0 0-.447.276" />
                                    <path
                                        d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM4.82 3a1.5 1.5 0 0 0-1.379.91l-.792 1.847a1.8 1.8 0 0 1-.853.904.8.8 0 0 0-.43.564L1.03 8.904a1.5 1.5 0 0 0-.03.294v.413c0 .796.62 1.448 1.408 1.484 1.555.07 3.786.155 5.592.155s4.037-.084 5.592-.155A1.48 1.48 0 0 0 15 9.611v-.413q0-.148-.03-.294l-.335-1.68a.8.8 0 0 0-.43-.563 1.8 1.8 0 0 1-.853-.904l-.792-1.848A1.5 1.5 0 0 0 11.18 3z" />
                                </svg>
                            </a>
                        </div>

                        {{-- Profile --}}
                        <a href="{{ route('customer.profile') }}"
                            class="flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $isProfile ? $activeClass : $inactiveClass }}">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                            </svg>
                            <span
                                class="text-[10px] font-black mt-1 tracking-tight {{ $isProfile ? 'opacity-100' : 'opacity-70' }}">资料</span>
                            @if ($isProfile)
                                <div class="h-1 w-1 rounded-full bg-slate-900 mt-0.5"></div>
                            @endif
                        </a>

                        {{-- More --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false" type="button"
                                class="w-full flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $isMore ? $activeClass : $inactiveClass }}">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                                <span class="text-[10px] font-black mt-1 tracking-tight">更多</span>
                            </button>

                            {{-- Refined More Dropdown (Slide up animation) --}}
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                                class="absolute bottom-[84px] right-0 w-[18rem] rounded-[2rem] border border-slate-100 bg-white/95 backdrop-blur-xl shadow-[0_-10px_40px_rgba(0,0,0,0.12)] overflow-hidden p-2 ring-1 ring-black/5">

                                {{-- User Profile Header --}}
                                <div class="px-3 py-4 rounded-[1.5rem] bg-slate-50 border border-slate-100/50 mb-2">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-12 w-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-lg shadow-lg shadow-slate-200">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-black text-slate-900 truncate">
                                                {{ auth()->user()->name }}</div>
                                            <div
                                                class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                                Customer</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-1 px-1">

                                    {{-- 使用指南 --}}
                                    {{-- <a href="#"
                                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-indigo-50 transition group">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center group-active:scale-90 transition-transform">

                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75v10.5m-6-7.5h12" />
                                            </svg>

                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-800">使用指南</div>
                                            <div class="text-[11px] font-semibold text-slate-400">如何预约 · 流程说明</div>
                                        </div>
                                    </a> --}}

                                    {{-- 客服中心 --}}
                                    <a href="#"
                                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-emerald-50 transition group">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center group-active:scale-90 transition-transform">

                                            {{-- Headset Icon --}}
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 12a9 9 0 0118 0v3a3 3 0 01-3 3h-1v-6h4m-18 0h4v6H6a3 3 0 01-3-3v-3z" />
                                            </svg>

                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-800">客服中心</div>
                                            <div class="text-[11px] font-semibold text-slate-400">WhatsApp · 电话支持
                                            </div>
                                        </div>
                                    </a>

                                    {{-- 服务条款 --}}
                                    <a href="#"
                                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-amber-50 transition group">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center group-active:scale-90 transition-transform">

                                            {{-- Document Icon --}}
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12h6m-6 4h6M9 8h6m-7 12h8a2 2 0 002-2V7l-5-4H8a2 2 0 00-2 2v13a2 2 0 002 2z" />
                                            </svg>

                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-800">服务条款</div>
                                            <div class="text-[11px] font-semibold text-slate-400">使用规范说明</div>
                                        </div>
                                    </a>

                                    {{-- 隐私政策 --}}
                                    <a href="#"
                                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-indigo-50 transition group">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center group-active:scale-90 transition-transform">

                                            {{-- Shield Lock Icon --}}
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 3l8 4v5c0 5-3.5 9-8 9s-8-4-8-9V7l8-4z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12h6m-3-3v6" />
                                            </svg>

                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-800">隐私政策</div>
                                            <div class="text-[11px] font-semibold text-slate-400">数据保护政策</div>
                                        </div>
                                    </a>

                                    <div class="h-px bg-slate-100 my-3 mx-2"></div>

                                    {{-- Logout --}}
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 p-3 rounded-2xl text-rose-600 hover:bg-rose-50 transition group">

                                            <div
                                                class="h-10 w-10 rounded-xl bg-rose-100 flex items-center justify-center">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                                </svg>
                                            </div>

                                            <div>
                                                <div class="text-sm font-black">退出登录</div>
                                                <div class="text-[11px] font-semibold text-rose-400">安全退出账户</div>
                                            </div>

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
                            class="h-6 w-6 rounded bg-slate-900 flex items-center justify-center text-[10px] font-bold text-white">
                            C</div>
                        <p class="text-[13px] text-slate-500 font-medium">
                            © {{ date('Y') }} {{ config('app.name') }}。为每一段旅程而生。
                        </p>
                    </div>

                    <div class="flex items-center gap-8 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <a href="#" class="hover:text-slate-900 transition-colors">使用指南</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">客服中心</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">服务条款</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">隐私政策</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>
</body>

</html>
