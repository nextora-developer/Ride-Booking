@extends('layouts.customer-app')

@if ($activeBooking)
    <meta http-equiv="refresh" content="10">
@endif

@section('title', '首页')

@section('header')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center gap-2 justify-center md:justify-start">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    你好，{{ explode(' ', auth()->user()->name)[0] }}！
                </h1>
            </div>
            <p class="text-slate-500 font-medium text-sm sm:text-base text-center md:text-left">
                今天想去哪里？您的专属车队已准备就绪。
            </p>
        </div>

        <div class="flex justify-center md:justify-end">
            <a href="{{ route('customer.book') }}"
                class="group w-full md:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-slate-900 text-white font-bold transition-all duration-300 hover:bg-slate-800 hover:shadow-xl hover:shadow-slate-200 hover:-translate-y-0.5 active:translate-y-0">
                <svg class="h-5 w-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>立即预约</span>
            </a>
        </div>
    </div>
@endsection

@section('content')

    {{-- Stats Grid --}}
    <div class="grid grid-cols-3 gap-3 sm:gap-6 mb-10">
        @php
            $stats = [
                [
                    'label' => '总行程',
                    'value' => $totalTrips ?? 0,
                    'color' => 'indigo',
                    'icon' =>
                        'M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.856.12-1.683.342-2.466',
                ],
                [
                    'label' => '进行中',
                    'value' => $inProgress ?? 0,
                    'color' => 'amber',
                    'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                ],
                [
                    'label' => '已完成',
                    'value' => $completed ?? 0,
                    'color' => 'emerald',
                    'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                ],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="bg-white border border-slate-100 rounded-[2rem] p-4 sm:p-6 shadow-sm ring-1 ring-slate-200/50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        {{ $stat['label'] }}
                    </span>

                    <div
                        class="p-1.5 sm:p-2 
                        bg-{{ $stat['color'] }}-50 
                        rounded-lg sm:rounded-xl 
                        text-{{ $stat['color'] }}-500">
                        <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                        </svg>
                    </div>
                </div>

                <div class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    {{ $stat['value'] }}
                </div>
            </div>
        @endforeach
    </div>

    <div id="activeRideWrap">
        @include('customer.partials.active-ride', ['activeBooking' => $activeBooking ?? null])
    </div>

    {{-- Services Section --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6 px-1">
            <h2 class="text-xl font-black text-slate-900 tracking-tight">精选服务</h2>
            <div class="h-px flex-1 mx-4 bg-slate-100 hidden sm:block"></div>
            {{-- <a href="#" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                查看全部
            </a> --}}
        </div>

        @php
            $services = [
                [
                    'name' => '接送服务',
                    'desc' => '最快速直达目的地',
                    'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
                    'theme' => 'bg-blue-50 text-blue-600',
                ],
                [
                    'name' => '包车服务',
                    'desc' => '按小时计费，随叫随到',
                    'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                    'theme' => 'bg-purple-50 text-purple-600',
                ],
                [
                    'name' => '代驾服务',
                    'desc' => '安全护送，饮酒无忧',
                    'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                    'theme' => 'bg-emerald-50 text-emerald-600',
                ],
                [
                    'name' => '代购服务',
                    'desc' => '生活所需，跑腿速达',
                    'icon' =>
                        'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z',
                    'theme' => 'bg-rose-50 text-rose-600',
                ],
                [
                    'name' => '多人包车（大车）',
                    'desc' => '空间更大，最多可载10人',
                    'icon' =>
                        'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    'theme' => 'bg-orange-50 text-orange-600',
                ],
                [
                    'name' => '专属司机',
                    'desc' => '专业司机随时待命',
                    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                    'theme' => 'bg-slate-100 text-slate-800',
                ],
            ];
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($services as $service)
                <a href="{{ route('customer.book') }}"
                    class="group relative bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm transition-all duration-300 hover:shadow-2xl hover:shadow-slate-200 hover:-translate-y-2 ring-1 ring-slate-200/50">
                    <div class="flex flex-col gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $service['theme'] }} group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $service['icon'] }}" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors">
                                {{ $service['name'] }}
                            </h3>
                            <p class="text-xs text-slate-400 font-bold mt-1 line-clamp-1">
                                {{ $service['desc'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Arrow indicator that appears on hover --}}
                    <div
                        class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-all group-hover:translate-x-1">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const wrap = document.getElementById('activeRideWrap');
            if (!wrap) return;

            let timer = null;

            async function refreshActiveRide() {
                // 页面切到后台就不刷，省资源
                if (document.hidden) return;

                try {
                    const res = await fetch("{{ route('customer.active.ride') }}", {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    });

                    if (!res.ok) return;

                    const html = await res.text();
                    wrap.innerHTML = html;
                } catch (e) {
                    // 静默失败，不打扰用户
                }
            }

            // 先刷一次（避免刚好状态更新但你页面没变）
            refreshActiveRide();

            timer = setInterval(refreshActiveRide, 10000);

            // 离开页面时清掉
            window.addEventListener('beforeunload', () => {
                if (timer) clearInterval(timer);
            });
        })();
    </script>
@endpush
