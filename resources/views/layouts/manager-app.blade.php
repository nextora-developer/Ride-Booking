<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' - 派单端')</title>

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

        {{-- Sticky Topbar (Desktop) --}}
        <header class="hidden md:block sticky top-0 z-50 w-full border-b border-gray-100 bg-white/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between gap-8">

                    {{-- Left: Brand / Logo --}}
                    <div class="flex-1 md:flex-none">
                        <a href="{{ route('manager.dashboard') }}"
                            class="hidden md:flex items-center gap-3 hover:opacity-90 transition-opacity">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm ring-1 ring-slate-900/5">
                                <span class="font-extrabold text-lg tracking-tighter">M</span>
                            </div>
                            <div class="hidden sm:block leading-none">
                                <div class="text-[14px] font-bold text-slate-900 tracking-tight">Car Team</div>
                                <div class="text-[11px] text-slate-400 font-semibold">派单控制台</div>
                            </div>
                        </a>
                    </div>

                    {{-- Middle: Nav (Desktop/Tablet) --}}
                    @php
                        $isHome = request()->routeIs('manager.dashboard');
                        $isOrders = request()->routeIs('manager.orders.*');
                        $isDrivers = request()->routeIs('manager.drivers.*');
                        $isCredit = request()->routeIs('manager.credit-customers.*');
                        $isMore = request()->routeIs('manager.more*') || request()->routeIs('manager.profile*');
                    @endphp

                    <nav class="hidden md:flex items-center bg-gray-100/50 p-1 rounded-2xl border border-gray-100">
                        <a href="{{ route('manager.dashboard') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isHome ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            首页
                        </a>

                        <a href="{{ route('manager.orders.index') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isOrders ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            订单
                        </a>

                        {{-- Drivers（如果你还没 route，就先改成 #，不然会爆） --}}
                        <a href="{{ Route::has('manager.drivers.index') ? route('manager.drivers.index') : '#' }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isDrivers ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            司机
                        </a>

                        <a href="{{ Route::has('manager.credit-customers.index') ? route('manager.credit-customers.index') : '#' }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                    {{ $isCredit ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            挂单顾客
                        </a>

                        {{-- More --}}
                        <div class="relative group">
                            <button type="button"
                                class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all inline-flex items-center gap-1
                                       {{ $isMore ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                                更多
                                <svg class="h-3.5 w-3.5 opacity-60" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            {{-- Dropdown --}}
                            <div
                                class="absolute left-0 mt-2 w-52 rounded-2xl border border-gray-100 bg-white shadow-xl p-2
                                       opacity-0 pointer-events-none scale-95 group-hover:opacity-100 group-hover:pointer-events-auto group-hover:scale-100
                                       transition-all duration-200 origin-top-left">
                                <a href="#"
                                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                    顾客列表
                                </a>
                                <a href="#"
                                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                    支持中心
                                </a>
                            </div>
                        </div>
                    </nav>

                    {{-- Right: User Info (Desktop) --}}
                    <div class="flex items-center gap-4">
                        <div class="hidden lg:flex flex-col items-end leading-none">
                            <span class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] text-slate-400 font-semibold">派单员</span>
                        </div>

                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

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
                                <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
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

        {{-- Mobile Bottom Nav (像 customer 一样：含渐变 blur 背景 + active dot) --}}
        <nav class="md:hidden fixed bottom-0 inset-x-0 z-50">
            {{-- Blur background --}}
            <div
                class="absolute inset-0 bg-gradient-to-t from-slate-100/80 to-transparent backdrop-blur-sm -z-10 h-24 mt-auto">
            </div>

            <div class="mx-auto max-w-7xl px-4 pb-[max(env(safe-area-inset-bottom),16px)]">
                <div
                    class="relative rounded-[2.5rem] border border-white/50 bg-white/90 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.12)] ring-1 ring-slate-900/5">
                    <div class="grid grid-cols-5 gap-1 p-2 items-center">

                        @php
                            $mHome = request()->routeIs('manager.dashboard');
                            $mOrders = request()->routeIs('manager.orders.*');
                            $mDrivers = request()->routeIs('manager.drivers.*');
                            $mCredit = request()->routeIs('manager.credit-customers.*');
                            $mMore = false;

                            $activeClass = 'text-slate-900 scale-110';
                            $inactiveClass = 'text-slate-400 hover:text-slate-600';
                        @endphp

                        {{-- Home --}}
                        <a href="{{ route('manager.dashboard') }}"
                            class="flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $mHome ? $activeClass : $inactiveClass }}">
                            <svg class="h-6 w-6" fill="{{ $mHome ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12 11.204 3.045a1.125 1.125 0 0 1 1.591 0L21.75 12M4.5 9.75V20.25A1.5 1.5 0 0 0 6 21.75h3.75v-6a1.5 1.5 0 0 1 1.5-1.5h1.5a1.5 1.5 0 0 1 1.5 1.5v6H18a1.5 1.5 0 0 0 1.5-1.5V9.75" />
                            </svg>
                            <span
                                class="text-[10px] font-black mt-1 tracking-tight {{ $mHome ? 'opacity-100' : 'opacity-70' }}">首页</span>
                            @if ($mHome)
                                <div class="h-1 w-1 rounded-full bg-slate-900 mt-0.5"></div>
                            @endif
                        </a>

                        {{-- Orders --}}
                        <a href="{{ route('manager.orders.index') }}"
                            class="flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $mOrders ? $activeClass : $inactiveClass }}">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6l4 2M21 12a9 9 0 11-3.3-6.9" />
                            </svg>
                            <span
                                class="text-[10px] font-black mt-1 tracking-tight {{ $mOrders ? 'opacity-100' : 'opacity-70' }}">订单</span>
                            @if ($mOrders)
                                <div class="h-1 w-1 rounded-full bg-slate-900 mt-0.5"></div>
                            @endif
                        </a>

                        {{-- Drivers --}}
                        <a href="{{ Route::has('manager.drivers.index') ? route('manager.drivers.index') : '#' }}"
                            class="flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $mDrivers ? $activeClass : $inactiveClass }}">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2.25c-4.142 0-7.5 2.239-7.5 5v.75h15v-.75c0-2.761-3.358-5-7.5-5Z" />
                            </svg>
                            <span
                                class="text-[10px] font-black mt-1 tracking-tight {{ $mDrivers ? 'opacity-100' : 'opacity-70' }}">司机</span>
                            @if ($mDrivers)
                                <div class="h-1 w-1 rounded-full bg-slate-900 mt-0.5"></div>
                            @endif
                        </a>

                        {{-- Credit Customers --}}
                        <a href="{{ Route::has('manager.credit-customers.index') ? route('manager.credit-customers.index') : '#' }}"
                            class="flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $mCredit ? $activeClass : $inactiveClass }}">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>

                            <span
                                class="text-[10px] font-black mt-1 tracking-tight {{ $mCredit ? 'opacity-100' : 'opacity-70' }}">挂单</span>
                            @if ($mCredit)
                                <div class="h-1 w-1 rounded-full bg-slate-900 mt-0.5"></div>
                            @endif
                        </a>

                        {{-- More (像 customer：大卡片 dropdown) --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false" type="button"
                                class="w-full flex flex-col items-center justify-center py-2 transition-all duration-300 {{ $mMore ? $activeClass : $inactiveClass }}">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                                <span class="text-[10px] font-black mt-1 tracking-tight">更多</span>
                            </button>

                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                                class="absolute bottom-[84px] right-0 w-[18rem] rounded-[2rem] border border-slate-100 bg-white/95 backdrop-blur-xl shadow-[0_-10px_40px_rgba(0,0,0,0.12)] overflow-hidden p-2 ring-1 ring-black/5">

                                {{-- Header --}}
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
                                                派单员</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-1 px-1">
                                    <a href="#"
                                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition group">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-active:scale-90 transition-transform">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7.5 8.25h9m-9 3h9m-9 3h6" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-800">派单规范</div>
                                            <div class="text-[11px] font-semibold text-slate-400">流程 · 注意事项</div>
                                        </div>
                                    </a>

                                    <a href="#"
                                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-indigo-50 transition group">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center group-active:scale-90 transition-transform">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 12a9 9 0 0118 0v3a3 3 0 01-3 3h-1v-6h4m-18 0h4v6H6a3 3 0 01-3-3v-3z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-800">技术支持</div>
                                            <div class="text-[11px] font-semibold text-slate-400">联系管理员 · 协助处理</div>
                                        </div>
                                    </a>

                                    <div class="h-px bg-slate-100 my-3 mx-2"></div>

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

        {{-- Footer (Desktop) --}}
        <footer class="hidden md:block mt-auto border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-2">
                        <div
                            class="h-6 w-6 rounded bg-slate-900 flex items-center justify-center text-[10px] font-bold text-white">
                            M</div>
                        <p class="text-[13px] text-slate-500 font-medium">
                            © {{ date('Y') }} {{ config('app.name') }}. 派单控制台。
                        </p>
                    </div>

                    <div class="flex items-center gap-8 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <a href="#" class="hover:text-slate-900 transition-colors">隐私政策</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">派单规范</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">技术支持</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>
</body>

</html>
