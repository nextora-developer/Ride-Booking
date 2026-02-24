<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name').' - Admin')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-50">

    {{-- Admin Topbar --}}
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-black text-white flex items-center justify-center font-bold">
                    A
                </div>
                <div>
                    <div class="text-sm font-bold leading-4">Admin Panel</div>
                    <div class="text-xs text-gray-500">Boss Control</div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-3 py-2 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-100">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-2 rounded-lg text-sm font-semibold bg-black text-white">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    @hasSection('header')
        <header class="max-w-7xl mx-auto px-4 py-6">
            @yield('header')
        </header>
    @endif

    <main class="max-w-7xl mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-800 text-sm">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-800 text-sm">
                <div class="font-semibold mb-2">Please fix the errors below:</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>