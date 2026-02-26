@extends('layouts.customer-app')

@section('title', '顾客仪表板')

@section('header')
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 text-center md:text-left">
        <div class="mx-auto md:mx-0">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                欢迎回来，{{ explode(' ', auth()->user()->name)[0] }}！
            </h1>
            <p class="text-slate-500 font-medium mt-1">
                您的下一段行程，只需轻轻一点。
            </p>
        </div>

        <div class="flex justify-center md:justify-end">
            <a href="{{ route('customer.book') }}"
                class="w-full md:w-auto
               inline-flex items-center justify-center gap-2
               px-6 py-4 md:py-3
               rounded-3xl
               bg-gradient-to-r from-slate-900 to-slate-800
               text-white
               font-extrabold tracking-tight
               shadow-[0_12px_30px_rgba(0,0,0,0.15)]
               hover:shadow-[0_18px_40px_rgba(0,0,0,0.25)]
               hover:-translate-y-1
               active:translate-y-0 active:shadow-lg
               transition-all duration-300">

                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>

                立即预约
            </a>
        </div>
    </div>
@endsection

@section('content')

    {{-- 快捷数据（手机：一排3个，App风格） --}}
    <div class="grid grid-cols-3 gap-3 sm:gap-5 mb-8 sm:mb-10">

        {{-- 行程总数 --}}
        <div class="bg-white border border-gray-100 rounded-3xl p-3 sm:p-6">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    行程
                </span>
                <div class="p-1.5 sm:p-2 bg-slate-50 rounded-lg text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.856.12-1.683.342-2.466" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-4xl font-black mt-2 sm:mt-3 text-slate-900">
                {{ $totalTrips ?? 0 }}
            </div>
        </div>

        {{-- 进行中 --}}
        <div class="bg-white border border-gray-100 rounded-3xl p-3 sm:p-6">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    进行中
                </span>
                <div class="p-1.5 sm:p-2 bg-amber-50 rounded-lg text-amber-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-4xl font-black mt-2 sm:mt-3 text-amber-600">
                {{ $inProgress ?? 0 }}
            </div>
        </div>

        {{-- 已完成 --}}
        <div class="bg-white border border-gray-100 rounded-3xl p-3 sm:p-6">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    已完成
                </span>
                <div class="p-1.5 sm:p-2 bg-emerald-50 rounded-lg text-emerald-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-4xl font-black mt-2 sm:mt-3 text-emerald-600">
                {{ $completed ?? 0 }}
            </div>
        </div>

    </div>

    {{-- 服务入口（手机：一排2个，App卡片风格） --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5 px-1">
            <h2 class="text-lg font-bold text-slate-900">我们的服务</h2>
            <a href="#" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                查看全部
            </a>
        </div>

        @php
            $services = [
                [
                    'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
                    'name' => '接送服务',
                    'desc' => '最快速直达目的地',
                ],
                [
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'name' => '包车服务',
                    'desc' => '按小时包车，灵活出行',
                ],
                [
                    'icon' =>
                        'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'name' => '代驾服务',
                    'desc' => '安全驾驶您的爱车',
                ],
                [
                    'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                    'name' => '代购服务',
                    'desc' => '代您购买并送达',
                ],
                [
                    'icon' =>
                        'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    'name' => '多人包车（大车）',
                    'desc' => '空间更大，最多可载10人',
                ],
                [
                    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                    'name' => '专属司机',
                    'desc' => '专业司机随时待命',
                ],
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($services as $service)
                <div
                    class="group bg-white rounded-3xl border border-gray-100 p-5 md:p-6
                           hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/60 transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div
                            class="flex items-center justify-center h-14 w-14 rounded-2xl bg-slate-100 text-slate-900
                                   group-hover:bg-slate-900 group-hover:text-white transition-colors duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $service['icon'] }}" />
                            </svg>
                        </div>

                        <h3 class="text-sm md:text-base font-bold text-slate-900 leading-tight">
                            {{ $service['name'] }}
                        </h3>

                        <p class="hidden md:block text-xs text-slate-400 font-medium">
                            {{ $service['desc'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
