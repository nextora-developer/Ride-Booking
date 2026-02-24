<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' - Manager')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900 selection:bg-pink-100 selection:text-pink-700">
    <div class="min-h-screen bg-[#fbfbfc] flex flex-col">

        {{-- Sticky Topbar --}}
        <header class="sticky top-0 z-50 w-full border-b border-gray-100 bg-white/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between gap-8">

                    {{-- Left: Logo --}}
                    <a href="{{ route('manager.dashboard') }}"
                        class="flex items-center gap-3 hover:opacity-90 transition-opacity">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm ring-1 ring-slate-900/5">
                            <span class="font-extrabold text-lg tracking-tighter">M</span>
                        </div>
                        <div class="hidden sm:block leading-none">
                            <div class="text-[14px] font-bold text-slate-900 tracking-tight">Car Team</div>
                            <div class="text-[11px] text-slate-400 font-semibold">Manager Console</div>
                        </div>
                    </a>

                    {{-- Middle: Nav (Desktop/Tablet) --}}
                    @php
                        $isHome = request()->routeIs('manager.dashboard');
                        $isOrders = request()->routeIs('manager.orders.*');

                        // 你还没做 drivers/customers 的话，这两个先留着（不会影响）
                        $isDrivers = request()->routeIs('manager.drivers.*');
                        $isUsers = request()->routeIs('manager.customers.*');
                    @endphp

                    <nav class="hidden md:flex items-center bg-gray-100/50 p-1 rounded-2xl border border-gray-100">
                        <a href="{{ route('manager.dashboard') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isHome ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            Home
                        </a>

                        <a href="{{ route('manager.orders.index') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isOrders ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            Orders
                        </a>

                        {{-- Drivers (你还没做 routes 就先用 #，避免爆) --}}
                        <a href="{{ route('manager.drivers.index') }}"
                            class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ $isDrivers ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                            Drivers
                        </a>

                        <div class="relative group">
                            <button type="button"
                                class="px-4 py-1.5 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-900 transition-all inline-flex items-center gap-1">
                                More
                                <svg class="h-3.5 w-3.5 opacity-60" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div
                                class="absolute left-0 mt-2 w-52 rounded-2xl border border-gray-100 bg-white shadow-xl p-2
                                       opacity-0 pointer-events-none scale-95 group-hover:opacity-100 group-hover:pointer-events-auto group-hover:scale-100
                                       transition-all duration-200 origin-top-left">
                                {{-- Customers (你还没做 routes 就先用 #，避免爆) --}}
                                <a href="#"
                                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                    Customers
                                </a>
                                <a href="#"
                                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                    Support Center
                                </a>
                                <a href="#"
                                    class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                    Terms of Service
                                </a>
                            </div>
                        </div>
                    </nav>

                    {{-- Right: User Info (Desktop) --}}
                    <div class="flex items-center gap-4">
                        <div class="hidden lg:flex flex-col items-end leading-none">
                            <span class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] text-slate-400 font-semibold">Manager</span>
                        </div>

                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

                        <form method="POST" action="{{ route('logout') }}" class="hidden md:flex items-center gap-3">
                            @csrf
                            <button class="text-sm font-semibold text-slate-500 hover:text-rose-600 transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </header>

        {{-- Main --}}
        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-28 md:pb-8">

                @if (View::hasSection('header'))
                    <div class="mb-8">@yield('header')</div>
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
                                Check your inputs
                            </div>
                            <ul class="list-disc pl-5 space-y-0.5 opacity-90">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="relative">
                    @yield('content')
                </div>
            </div>
        </main>

        {{-- Mobile Bottom Nav --}}
        <nav class="md:hidden fixed bottom-0 inset-x-0 z-50">
            <div class="mx-auto max-w-7xl px-4 pb-[max(env(safe-area-inset-bottom),12px)]">
                <div
                    class="rounded-3xl border border-gray-200 bg-white/85 backdrop-blur-xl shadow-[0_18px_40px_rgba(0,0,0,0.10)]">
                    <div class="grid grid-cols-4 gap-1 p-2">

                        {{-- Home --}}
                        <a href="{{ route('manager.dashboard') }}"
                            class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 transition-all
                                  {{ $isHome ? 'bg-slate-900 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12 11.204 3.045a1.125 1.125 0 0 1 1.591 0L21.75 12M4.5 9.75V20.25A1.5 1.5 0 0 0 6 21.75h3.75v-6a1.5 1.5 0 0 1 1.5-1.5h1.5a1.5 1.5 0 0 1 1.5 1.5v6H18a1.5 1.5 0 0 0 1.5-1.5V9.75" />
                            </svg>
                            <span class="text-[11px] font-extrabold">Home</span>
                        </a>

                        {{-- Orders --}}
                        <a href="{{ route('manager.orders.index') }}"
                            class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 transition-all
                                  {{ $isOrders ? 'bg-slate-900 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7.5h18M6 7.5V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v1.5M6 7.5v12A2.25 2.25 0 0 0 8.25 21.75h7.5A2.25 2.25 0 0 0 18 19.5v-12" />
                            </svg>
                            <span class="text-[11px] font-extrabold">Orders</span>
                        </a>

                        {{-- Drivers (未做 routes 先用 #) --}}
                        <a href="#"
                            class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 transition-all
                                  {{ $isDrivers ? 'bg-slate-900 text-white' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2.25c-4.142 0-7.5 2.239-7.5 5v.75h15v-.75c0-2.761-3.358-5-7.5-5Z" />
                            </svg>
                            <span class="text-[11px] font-extrabold">Drivers</span>
                        </a>

                        {{-- More --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open=!open" @click.outside="open=false" type="button"
                                class="w-full flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 transition-all text-slate-500 hover:text-slate-900 hover:bg-slate-50">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                                <span class="text-[11px] font-extrabold">More</span>
                            </button>

                            <div x-cloak x-show="open"
                                class="absolute bottom-[72px] right-0 w-56 rounded-2xl border border-gray-100 bg-white shadow-2xl overflow-hidden">
                                <div class="p-2">
                                    <div class="px-3 py-2">
                                        <div class="text-xs font-black text-slate-900 truncate">
                                            {{ auth()->user()->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-semibold">Manager</div>
                                    </div>

                                    <div class="h-px bg-gray-100 my-1"></div>

                                    <a href="#"
                                        class="block px-3 py-2 rounded-xl text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                        Customers
                                    </a>

                                    <div class="h-px bg-gray-100 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-3 py-2 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </nav>

        {{-- Footer --}}
        <footer class="hidden md:block mt-auto border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-2">
                        <div
                            class="h-6 w-6 rounded bg-slate-900 flex items-center justify-center text-[10px] font-bold text-white">
                            M</div>
                        <p class="text-[13px] text-slate-500 font-medium">
                            © {{ date('Y') }} {{ config('app.name') }}. Manager Console.
                        </p>
                    </div>

                    <div class="flex items-center gap-8 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <a href="#" class="hover:text-slate-900 transition-colors">Privacy</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">Guidelines</a>
                        <a href="#" class="hover:text-slate-900 transition-colors">Support</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>
</body>

</html>
