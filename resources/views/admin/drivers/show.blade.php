@extends('layouts.admin-app')

@section('title', 'Driver Details')

@section('header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        {{-- LEFT: Driver Info --}}
        <div class="min-w-0">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 truncate">
                {{ $driver->name }}
            </h1>

            <p class="mt-2 text-sm text-slate-500 font-semibold">
                Driver ID: {{ $driver->id }}
                • Joined {{ optional($driver->created_at)->format('d M Y, h:i A') }}
            </p>
        </div>

        {{-- RIGHT: Back Button --}}
        <div class="shrink-0">
            <a href="{{ route('admin.drivers.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl
                       bg-white border border-gray-200
                       text-xs font-black uppercase tracking-widest
                       hover:bg-gray-50 transition">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                </svg>
                Back
            </a>
        </div>

    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 items-start">

        {{-- LEFT: 业务数据与订单 --}}
        <div class="xl:col-span-2 space-y-8">

            {{-- Stats Dashboard --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach ([['Total Orders', $stats['total'], 'bg-slate-900', 'text-white'], ['Active Now', $stats['active'], 'bg-emerald-500', 'text-white'], ['Completed', $stats['completed'], 'bg-white', 'text-slate-900']] as [$label, $value, $bg, $text])
                    <div
                        class="rounded-[2rem] {{ $bg }} {{ $text }} border border-slate-100 shadow-sm p-6 relative overflow-hidden group transition-transform hover:scale-[1.02]">
                        <div class="relative z-10">
                            <div class="text-[10px] font-black tracking-[0.2em] uppercase opacity-70">{{ $label }}
                            </div>
                            <div class="mt-2 text-4xl font-black tracking-tighter">{{ $value }}</div>
                        </div>
                        {{-- 装饰性背景小图标 --}}
                        <div class="absolute -right-2 -bottom-2 opacity-10 group-hover:scale-110 transition-transform">
                            <svg class="h-20 w-20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Recent Orders Activity --}}
            <div class="rounded-[2.5rem] bg-white border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Recent Activity</h3>
                        <p class="text-[11px] text-slate-400 font-bold mt-1 uppercase">Last 10 logistical tasks handled</p>
                    </div>
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                <div class="divide-y divide-slate-50">
                    @forelse($orders as $o)
                        <a href="{{ route('admin.orders.show', $o) }}"
                            class="group block px-8 py-5 hover:bg-slate-50 transition-all">
                            <div class="flex items-center justify-between gap-6">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400 group-hover:bg-white transition-colors">
                                        #{{ substr($o->id, -3) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div
                                            class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors">
                                            ORD-{{ str_pad((string) $o->id, 6, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div class="mt-1 flex items-center gap-2 text-[11px] font-bold text-slate-400">
                                            <span class="truncate max-w-[120px]">{{ $o->pickup }}</span>
                                            <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="3">
                                                <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                            </svg>
                                            <span class="truncate max-w-[120px]">{{ $o->dropoff }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                                        {{ $o->status === 'completed' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $o->status ?? 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="py-20 text-center">
                            <div class="text-slate-300 mb-2 font-black uppercase text-[10px] tracking-widest">No Active
                                Records</div>
                            <p class="text-slate-400 text-xs font-semibold">This driver hasn't accepted any orders yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT: 司机个人档案 --}}
        <div class="xl:col-span-2">
            <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-6">

                {{-- Header --}}
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div
                            class="h-16 w-16 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-xl font-black shadow-md">
                            {{ strtoupper(mb_substr($driver->name ?? 'D', 0, 1)) }}
                        </div>
                        <div
                            class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full {{ $driver->is_online ? 'bg-emerald-500' : 'bg-slate-300' }} border-2 border-white">
                        </div>
                    </div>

                    <div class="min-w-0">
                        <div class="text-lg font-black text-slate-900 truncate">
                            {{ $driver->name }}
                        </div>
                        <div class="text-xs font-semibold text-slate-500 truncate">
                            {{ $driver->email ?? 'no-email@system.com' }}
                        </div>
                    </div>
                </div>

                {{-- Info Sections --}}
                <div class="mt-6 space-y-6 text-sm">

                    {{-- PERSONAL --}}
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                            Personal
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ([['Full Name', $driver->full_name], ['IC Number', $driver->ic_number], ['Phone', $driver->phone], ['Shift', strtoupper($driver->shift)]] as [$label, $val])
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        {{ $label }}
                                    </div>
                                    <div class="mt-1 font-bold text-slate-900">
                                        {{ $val ?? '—' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- VEHICLE --}}
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                            Vehicle
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ([['Car Plate', $driver->car_plate], ['Car Model', $driver->car_model]] as [$label, $val])
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        {{ $label }}
                                    </div>
                                    <div class="mt-1 font-bold text-slate-900 tracking-wider">
                                        {{ $val ?? '—' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- BANK --}}
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                            Bank
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ([['Bank Name', $driver->bank_name], ['Bank Account', $driver->bank_account]] as [$label, $val])
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        {{ $label }}
                                    </div>
                                    <div class="mt-1 font-bold text-slate-900">
                                        {{ $val ?? '—' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Edit Button --}}

                <div class="mt-6">
                    <a href="{{ route('admin.drivers.edit', $driver) }}"
                        class="block w-full py-3 rounded-xl bg-slate-900 text-center text-white text-xs font-black uppercase tracking-widest hover:bg-black transition-all duration-200">
                        Edit Profile
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection
