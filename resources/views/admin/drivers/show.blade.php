@extends('layouts.admin-app')

@section('title', 'Driver Details')

@section('header')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <a href="{{ route('admin.drivers.index') }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-gray-200 text-xs font-black hover:bg-gray-50">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                </svg>
                Back
            </a>

            <h1 class="mt-3 text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 truncate">
                {{ $driver->name }}
            </h1>
            <p class="mt-2 text-sm text-slate-500 font-semibold">
                Driver ID: {{ $driver->id }} • Joined {{ optional($driver->created_at)->format('d M Y, h:i A') }}
            </p>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5">
                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">Total Orders</div>
                    <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['total'] }}</div>
                </div>

                <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5">
                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">Active</div>
                    <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['active'] }}</div>
                </div>

                <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5">
                    <div class="text-xs font-black tracking-widest uppercase text-slate-400">Completed</div>
                    <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['completed'] }}</div>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-5 border-b border-gray-100">
                    <div class="text-sm font-extrabold text-slate-900">Recent Orders</div>
                    <div class="text-xs text-slate-500 font-semibold mt-1">Latest 10 orders handled by this driver.</div>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($orders as $o)
                        <a href="{{ route('admin.orders.show', $o) }}" class="block px-5 sm:px-6 py-4 hover:bg-gray-50/60">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-extrabold text-slate-900 truncate">
                                        ORD-{{ str_pad((string)$o->id, 6, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="text-xs text-slate-500 font-semibold truncate">
                                        {{ $o->pickup }} → {{ $o->dropoff }}
                                    </div>
                                </div>

                                <div class="text-xs font-black rounded-full px-2.5 py-1 bg-gray-100 text-gray-700">
                                    {{ strtoupper($o->status ?? '-') }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 sm:px-6 py-10 text-center text-sm text-slate-500 font-semibold">
                            No orders yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="space-y-6">
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-5 sm:p-6">
                <div class="text-xs font-black tracking-widest uppercase text-slate-400">Profile</div>

                <div class="mt-4 flex items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-black text-white flex items-center justify-center font-extrabold text-lg">
                        {{ strtoupper(mb_substr($driver->name ?? 'D', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-extrabold text-slate-900 truncate">{{ $driver->name }}</div>
                        <div class="text-xs text-slate-500 font-semibold truncate">{{ $driver->email ?? '—' }}</div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3">
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 text-sm">
                        <div class="font-extrabold text-slate-900">Phone</div>
                        <div class="mt-1 text-slate-700 font-semibold">{{ $driver->phone ?? '—' }}</div>
                    </div>

                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 text-sm">
                        <div class="font-extrabold text-slate-900">Shift</div>
                        <div class="mt-1 text-slate-700 font-semibold">{{ strtoupper($driver->shift ?? '—') }}</div>
                    </div>

                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 text-sm">
                        <div class="font-extrabold text-slate-900">Role</div>
                        <div class="mt-1 text-slate-700 font-semibold">{{ strtoupper($driver->role ?? 'DRIVER') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection