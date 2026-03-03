<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' - Admin')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-slate-900">
    <div x-data="{ sidebarOpen: false }" class="h-screen bg-gray-50 overflow-hidden">

        {{-- Mobile Topbar --}}
        <div class="lg:hidden sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
            <div class="px-4 py-3 flex items-center justify-between gap-3">
                <button type="button" @click="sidebarOpen = true"
                    class="inline-flex items-center justify-center h-10 w-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                    aria-label="Open menu">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- <div class="flex items-center gap-3 min-w-0">
                    <div class="h-9 w-9 rounded-xl bg-black text-white flex items-center justify-center font-extrabold">
                        A
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-extrabold leading-4 truncate">Admin Panel</div>
                        <div class="text-xs text-gray-500 truncate">Boss Control</div>
                    </div>
                </div> --}}

                {{-- mobile logout (optional keep) --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="h-10 px-3 rounded-xl text-sm font-semibold bg-black text-white">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Layout --}}
        <div class="flex h-full">

            {{-- Overlay (mobile) --}}
            <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40 lg:hidden"
                @click="sidebarOpen = false"></div>

            {{-- Sidebar --}}
            <aside x-cloak
                class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100
                       transform transition-transform duration-200 ease-out
                       lg:translate-x-0 lg:static lg:inset-auto lg:z-auto
                       flex flex-col h-screen"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

                {{-- Sidebar Header --}}
                <div class="h-16 px-5 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="h-9 w-9 rounded-xl bg-black text-white flex items-center justify-center font-extrabold">
                            A
                        </div>
                        <div>
                            <div class="text-sm font-extrabold leading-4">Admin Panel</div>
                            <div class="text-xs text-gray-500">Boss Control</div>
                        </div>
                    </div>

                    <button type="button"
                        class="lg:hidden inline-flex items-center justify-center h-9 w-9 rounded-xl border border-gray-200 hover:bg-gray-50"
                        @click="sidebarOpen = false" aria-label="Close menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>

                {{-- Sidebar Nav --}}
                @php
                    $navLink =
                        'flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition hover:bg-gray-50';
                    $navActive = 'bg-gray-100 text-gray-900';
                    $navIdle = 'text-gray-700';

                    $safeRoute = function (string $name) {
                        return \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';
                    };
                    $has = fn(string $name) => \Illuminate\Support\Facades\Route::has($name);
                @endphp

                {{-- ✅ scrollable area --}}
                <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen=false"
                        class="{{ $navLink }} {{ request()->routeIs('admin.dashboard') ? $navActive : $navIdle }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10.5l9-7 9 7V20a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-9.5z" />
                        </svg>
                        Dashboard
                    </a>

                    {{-- Orders --}}
                    <a href="{{ route('admin.orders.index') }}" @click="sidebarOpen=false"
                        class="{{ $navLink }} {{ request()->routeIs('admin.orders.*') ? $navActive : $navIdle }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 7h10M7 11h10M7 15h6M5 4h14a1 1 0 0 1 1 1v16l-3-2-3 2-3-2-3 2-3-2-3 2V5a1 1 0 0 1 1-1z" />
                        </svg>
                        Orders
                    </a>

                    {{-- Customer --}}
                    <a href="{{ $safeRoute('admin.customers.index') }}" @click="sidebarOpen=false"
                        class="{{ $navLink }}
                               {{ request()->routeIs('admin.customers.*') ? $navActive : $navIdle }}
                               {{ $has('admin.customers.index') ? '' : 'opacity-50 cursor-not-allowed' }}"
                        @if (!$has('admin.customers.index')) @click.prevent @endif>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
                        </svg>
                        Customer
                    </a>

                    {{-- Driver --}}
                    <a href="{{ $safeRoute('admin.drivers.index') }}" @click="sidebarOpen=false"
                        class="{{ $navLink }}
                               {{ request()->routeIs('admin.drivers.*') ? $navActive : $navIdle }}
                               {{ $has('admin.drivers.index') ? '' : 'opacity-50 cursor-not-allowed' }}"
                        @if (!$has('admin.drivers.index')) @click.prevent @endif>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>

                        Driver
                    </a>

                    {{-- Credit Logs --}}
                    <a href="{{ $safeRoute('admin.credit.logs.index') }}" @click="sidebarOpen=false"
                        class="{{ $navLink }}
                               {{ request()->routeIs('admin.credit.logs.*') ? $navActive : $navIdle }}
                               {{ $has('admin.credit.logs.index') ? '' : 'opacity-50 cursor-not-allowed' }}"
                        @if (!$has('admin.credit.logs.index')) @click.prevent @endif>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>

                        Credit Logs
                    </a>


                    {{-- Report --}}
                    <a href="{{ $safeRoute('admin.reports.index') }}" @click="sidebarOpen=false"
                        class="{{ $navLink }}
                               {{ request()->routeIs('admin.reports.*') ? $navActive : $navIdle }}
                               {{ $has('admin.reports.index') ? '' : 'opacity-50 cursor-not-allowed' }}"
                        @if (!$has('admin.reports.index')) @click.prevent @endif>

                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>

                        Report
                    </a>
                </nav>

                {{-- ✅ Logout fixed bottom --}}
                <div class="p-4 border-t border-gray-100 shrink-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold bg-black text-white hover:bg-gray-900 transition">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Content (independent scroll) --}}
            <div class="flex-1 min-w-0 overflow-y-auto">

                {{-- Desktop Topbar --}}
                <div class="hidden lg:block sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-gray-100">
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <span class="font-semibold text-gray-900">Admin</span> / @yield('title')
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="px-4 py-2 rounded-xl text-sm font-semibold bg-black text-white">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Page header --}}
                @hasSection('header')
                    <header class="px-4 sm:px-6 lg:px-8 py-6">
                        @yield('header')
                    </header>
                @endif

                <main class="px-4 sm:px-6 lg:px-8 pb-24 lg:pb-10">
                    @if (session('status'))
                        <div class="mb-6 p-4 rounded-2xl bg-green-50 text-green-800 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-2xl bg-red-50 text-red-800 text-sm">
                            <div class="font-semibold mb-2">Please fix the errors below:</div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>

</html>
