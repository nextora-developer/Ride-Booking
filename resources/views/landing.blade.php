<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Booking System</title>
    <meta name="description" content="专业叫车 / 派车 / 司机管理系统">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-slate-900 antialiased">

    <div x-data="{ pricing: false }">

        @include('partials.landing-header')

        {{-- Hero Banner --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-sky-600 via-blue-700 to-indigo-900">
            {{-- Animated Background Elements --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(255,255,255,0.15),transparent_40%)]">
                </div>

                {{-- Animated Ring 1 --}}
                <div
                    class="absolute right-[-10%] bottom-[-10%] h-[500px] w-[500px] rounded-full border-[1px] border-white/20 animate-[pulse_8s_infinite]">
                </div>
                {{-- Animated Ring 2 --}}
                <div
                    class="absolute right-[-5%] bottom-[-5%] h-[700px] w-[700px] rounded-full border-[1px] border-white/10 animate-[pulse_12s_infinite]">
                </div>

                {{-- Decorative Blobs --}}
                <div
                    class="absolute left-[-100px] top-[-100px] h-[400px] w-[400px] rounded-full bg-sky-400/20 blur-[120px]">
                </div>
                <div
                    class="absolute right-[10%] top-[10%] h-[300px] w-[300px] rounded-full bg-indigo-500/20 blur-[100px]">
                </div>
            </div>

            <div class="relative max-w-7xl mx-auto px-6 lg:px-8 min-h-[800px] flex items-center py-20 lg:py-32">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center w-full">

                    {{-- Left Content --}}
                    <div class="text-white z-10">
                        <div
                            class="inline-flex items-center rounded-full border border-white/30 bg-white/10 px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase backdrop-blur-md shadow-inner">
                            <span class="flex h-2 w-2 rounded-full bg-sky-400 mr-2 animate-pulse"></span>
                            Smart Ride Dispatch System
                        </div>

                        <h1 class="mt-8 text-5xl md:text-6xl xl:text-7xl font-extrabold leading-[1.05] tracking-tight">
                            专业的代驾 / 叫车<br />
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-white via-sky-100 to-white/70">管理系统</span>
                        </h1>

                        <p class="mt-8 text-lg lg:text-xl leading-relaxed text-blue-50/80 max-w-xl font-medium">
                            一套系统整合顾客预约、后台派单、司机接单及运营报表。专为代驾、接送及车队调度打造的高效数字化方案。
                        </p>

                        <div class="mt-12 flex flex-col sm:flex-row gap-5">
                            <a href="{{ route('login') }}"
                                class="group inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-xl shadow-blue-900/20 hover:bg-sky-50 transition-all duration-300 hover:-translate-y-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="mr-2 h-5 w-5 group-hover:rotate-12 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                                演示查看
                            </a>

                            <button @click="pricing=true"
                                class="inline-flex items-center justify-center rounded-2xl border border-white/30 bg-white/5 px-8 py-4 text-base font-bold text-white backdrop-blur-md hover:bg-white/10 transition-all duration-300 hover:-translate-y-1">
                                查看配套
                            </button>
                        </div>

                        <div class="mt-12 pt-8 border-t border-white/10">
                            <a href="#features"
                                class="group inline-flex items-center text-sm font-semibold text-white/70 hover:text-white transition-colors">
                                <span>探索核心功能</span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="ml-2 h-4 w-4 transform group-hover:translate-y-1 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Right Device Showcase --}}
                    <div class="relative hidden lg:flex items-center justify-center">
                        <div class="flex items-center gap-6">

                            {{-- Device 1 (Floating) --}}
                            <div class="relative w-[260px] animate-[float_6s_ease-in-out_infinite]">
                                <div
                                    class="rounded-[45px] bg-slate-900 p-3 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] border-t border-white/20">
                                    <div class="rounded-[35px] overflow-hidden bg-white aspect-[9/19] relative">
                                        {{-- Screen Content --}}
                                        <img src="{{ asset('images/customer-page.png') }}"
                                            class="w-full h-full object-cover" alt="Preview">
                                        {{-- Glass Overlay --}}
                                        <div
                                            class="absolute inset-0 pointer-events-none ring-1 ring-inset ring-black/10 rounded-[35px]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Device 2 (Delayed Floating) --}}
                            <div class="relative w-[260px] mt-20 animate-[float_6s_ease-in-out_2s_infinite]">
                                <div
                                    class="rounded-[45px] bg-slate-900 p-3 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] border-t border-white/20">
                                    <div class="rounded-[35px] overflow-hidden bg-white aspect-[9/19] relative">
                                        <img src="{{ asset('images/manager-page.png') }}"
                                            class="w-full h-full object-cover" alt="Preview">
                                        <div
                                            class="absolute inset-0 pointer-events-none ring-1 ring-inset ring-black/10 rounded-[35px]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-20px);
                }
            }
        </style>

        {{-- Intro --}}
        <section id="intro" class="py-20 scroll-mt-24 bg-slate-50/50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="text-xs font-black uppercase tracking-[0.3em] text-sky-500">System Introduction</div>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-black tracking-tight">一套系统，管理完整叫车业务流程</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">
                        从客户预约、后台调度、司机执行到订单完成与评价记录，
                        把原本分散的流程集中在同一套系统中，提升效率，也更方便管理。
                    </p>
                </div>
            </div>
        </section>

        {{-- Scenes --}}
        <section id="scenes" class="py-16 lg:py-24 scroll-mt-24 bg-white relative overflow-hidden">
            {{-- Decorative subtle background --}}
            <div class="absolute inset-0 pointer-events-none">
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent">
                </div>
                <div
                    class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent">
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center space-x-2">
                            <span class="h-px w-8 bg-sky-500"></span>
                            <span class="text-[11px] font-black uppercase tracking-[0.4em] text-sky-600">Use
                                Cases</span>
                        </div>
                        <h2 class="mt-4 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">
                            适用场景 <span class="text-sky-500">.</span>
                        </h2>
                        <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                            专为高频调度业务打造，一套系统轻松驾驭多种出行服务模式。
                        </p>
                    </div>

                    <div class="hidden lg:block">
                        <div class="flex -space-x-3">
                            {{-- Avatar group for social proof look --}}
                            @for ($i = 1; $i <= 4; $i++)
                                <div
                                    class="h-12 w-12 rounded-full border-4 border-white bg-slate-100 flex items-center justify-center text-xl shadow-sm">
                                    {{ ['🚗', '✈️', '💼', '🤝'][$i - 1] }}
                                </div>
                            @endfor
                            <div
                                class="h-12 flex items-center justify-center px-4 rounded-full border-4 border-white bg-sky-500 text-white text-xs font-bold shadow-sm">
                                +更多
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                    @php
                        $scenes = [
                            [
                                'title' => '即时叫车服务',
                                'desc' =>
                                    '顾客可快速提交叫车订单，系统即时同步到后台调度中心，适合城市短途出行或紧急用车需求。',
                                'icon' => 'M10.5 6L21 3m0 0h-5.25M21 3v5.25',
                                'color' => 'sky',
                                'emoji' => '🚗',
                            ],
                            [
                                'title' => '预约接送服务',
                                'desc' =>
                                    '支持提前预约行程，例如机场接送、酒店接送或定时用车，系统自动记录时间与行程信息。',
                                'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
                                'color' => 'indigo',
                                'emoji' => '✈️',
                            ],
                            [
                                'title' => '企业客户用车',
                                'desc' =>
                                    '支持企业客户下单与信用挂单管理，可用于商务接送、公司员工用车及长期合作客户。',
                                'icon' =>
                                    'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                'color' => 'slate',
                                'emoji' => '🏢',
                            ],
                            [
                                'title' => '车队调度管理',
                                'desc' =>
                                    '后台可查看所有订单并快速派单给在线司机，实时监控司机状态，提升整体运营效率。',
                                'icon' =>
                                    'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
                                'color' => 'blue',
                                'emoji' => '🚘',
                            ],
                        ];
                    @endphp

                    @foreach ($scenes as $scene)
                        <div
                            class="group relative flex flex-col bg-slate-50 rounded-[2.5rem] p-8 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 border border-transparent hover:border-slate-100">
                            <div
                                class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                <span class="text-3xl">{{ $scene['emoji'] }}</span>
                            </div>

                            <h3 class="text-xl font-black text-slate-900">{{ $scene['title'] }}</h3>
                            <p class="mt-4 text-slate-500 text-sm leading-relaxed flex-grow">
                                {{ $scene['desc'] }}
                            </p>

                            <div
                                class="mt-8 flex items-center text-sky-600 font-bold text-xs uppercase tracking-wider opacity-0 group-hover:opacity-100 transition-opacity">
                                <span>了解详情</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="features" class="py-16 lg:py-24 scroll-mt-24 bg-slate-50/50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                {{-- Section Header --}}
                <div class="flex flex-col items-center text-center mb-20">
                    <div
                        class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-sky-700">
                        Core Capabilities
                    </div>
                    <h2 class="mt-6 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">
                        全方位功能特色
                    </h2>
                    <p class="mt-4 text-lg text-slate-600 max-w-2xl leading-relaxed">
                        从预约到结算，每一个环节都经过精心设计，确保流程简洁高效，适配各类终端展示。
                    </p>
                </div>

                {{-- Feature Grid --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

                    {{-- Feature 1: Customer --}}
                    <div
                        class="group relative bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200 transition-all duration-300 hover:shadow-xl hover:border-sky-200">
                        <div
                            class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 transition-colors group-hover:bg-sky-500 group-hover:text-white">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-black text-slate-900">顾客预约系统</h3>

                        <p class="mt-4 text-sm leading-relaxed text-slate-500 font-medium">
                            顾客可快速提交叫车订单，填写上车地点、下车地点、时间与备注，
                            操作简单直观，适合手机端使用。
                        </p>

                        <ul class="mt-6 space-y-2 border-t border-slate-100 pt-6">
                            <li class="flex items-center text-xs text-slate-400 font-bold"><span
                                    class="mr-2 text-sky-500">✓</span> 上车 / 下车地点填写</li>
                            <li class="flex items-center text-xs text-slate-400 font-bold"><span
                                    class="mr-2 text-sky-500">✓</span> 预约时间与备注</li>
                        </ul>
                    </div>

                    {{-- Feature 2: Dispatch --}}
                    <div
                        class="group relative bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200 transition-all duration-300 hover:shadow-xl hover:border-indigo-200">
                        <div
                            class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-500 group-hover:text-white">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-black text-slate-900">调度派单系统</h3>

                        <p class="mt-4 text-sm leading-relaxed text-slate-500 font-medium">
                            调度后台可查看所有待处理订单，并根据司机在线状态、
                            班次与位置快速指派合适司机。
                        </p>

                        <ul class="mt-6 space-y-2 border-t border-slate-100 pt-6">
                            <li class="flex items-center text-xs text-slate-400 font-bold"><span
                                    class="mr-2 text-indigo-500">✓</span> 实时订单列表</li>
                            <li class="flex items-center text-xs text-slate-400 font-bold"><span
                                    class="mr-2 text-indigo-500">✓</span> 一键派单给司机</li>
                        </ul>
                    </div>

                    {{-- Feature 3: Status --}}
                    <div
                        class="group relative bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200 transition-all duration-300 hover:shadow-xl hover:border-emerald-200">
                        <div
                            class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 002.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-black text-slate-900">司机接单系统</h3>

                        <p class="mt-4 text-sm leading-relaxed text-slate-500 font-medium">
                            司机可在线接收订单，并更新行程状态，让顾客与后台
                            实时掌握订单进度。
                        </p>

                        <ul class="mt-6 space-y-2 border-t border-slate-100 pt-6">
                            <li class="flex items-center text-xs text-slate-400 font-bold"><span
                                    class="mr-2 text-emerald-500">✓</span> 在线 / 离线状态</li>
                            <li class="flex items-center text-xs text-slate-400 font-bold"><span
                                    class="mr-2 text-emerald-500">✓</span> 行程状态更新</li>
                        </ul>
                    </div>

                    {{-- Feature 4: Reports --}}
                    <div
                        class="group relative bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200 transition-all duration-300 hover:shadow-xl hover:border-amber-200">
                        <div
                            class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 transition-colors group-hover:bg-amber-500 group-hover:text-white">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-xl font-black text-slate-900">信用与数据报表</h3>

                        <p class="mt-4 text-sm leading-relaxed text-slate-500 font-medium">
                            系统内置信用挂单管理与运营报表功能，
                            方便管理员进行财务对账与数据统计。
                        </p>

                        <ul class="mt-6 space-y-2 border-t border-slate-100 pt-6">
                            <li class="flex items-center text-xs text-slate-400 font-bold"><span
                                    class="mr-2 text-amber-500">✓</span> 顾客信用挂单</li>
                            <li class="flex items-center text-xs text-slate-400 font-bold"><span
                                    class="mr-2 text-amber-500">✓</span> 订单与业绩报表</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        {{-- System Roles --}}
        <section id="roles" class="py-16 lg:py-24 scroll-mt-24 bg-slate-50 relative overflow-hidden">
            {{-- Decorative background element --}}
            <div
                class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent">
            </div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <div
                        class="inline-flex items-center rounded-full bg-white px-4 py-1.5 text-[11px] font-black uppercase tracking-[0.3em] text-sky-600 shadow-sm border border-slate-100">
                        System Roles
                    </div>
                    <h2 class="mt-6 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">
                        专人专界面，权限全掌控
                    </h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        针对不同业务链路角色量身定制，确保顾客下单顺畅、司机接单高效、管理调度精准。
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                    @php
                        $roles = [
                            [
                                'id' => 'Customer',
                                'name' => '顾客端',
                                'color' => 'sky',
                                'features' => [
                                    '预约下单',
                                    '查看订单状态',
                                    '历史订单追踪',
                                    '个人资料管理',
                                    '提交服务评价',
                                ],
                            ],
                            [
                                'id' => 'Driver',
                                'name' => '司机端',
                                'color' => 'emerald',
                                'features' => [
                                    '实时上线/下线',
                                    '即时订单接收',
                                    '状态一键更新',
                                    '接单收益统计',
                                    '司机资料认证',
                                ],
                            ],
                            [
                                'id' => 'Manager',
                                'name' => '调度中台',
                                'color' => 'indigo',
                                'features' => [
                                    '待处理订单池',
                                    '智能指派司机',
                                    '司机队列管理',
                                    '信用额度调整',
                                    '实时调度监控',
                                ],
                            ],
                            [
                                'id' => 'Admin',
                                'name' => '管理后台',
                                'color' => 'rose',
                                'features' => [
                                    '全量运营报表',
                                    '用户账户审计',
                                    '司机入驻审核',
                                    '全系统订单记录',
                                    '运营配置管理',
                                ],
                            ],
                        ];
                    @endphp

                    @foreach ($roles as $role)
                        <div
                            class="group relative flex flex-col bg-white rounded-[2.5rem] p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 transition-all duration-500 hover:shadow-2xl hover:shadow-{{ $role['color'] }}-500/10 hover:-translate-y-2">

                            {{-- Role Badge --}}
                            <div class="mb-6">
                                <div
                                    class="inline-flex text-[10px] font-black uppercase tracking-[0.2em] text-{{ $role['color'] }}-600 bg-{{ $role['color'] }}-50 px-3 py-1 rounded-lg">
                                    {{ $role['id'] }}
                                </div>
                                <h3 class="mt-4 text-2xl font-black text-slate-900">
                                    {{ $role['name'] }}
                                </h3>
                            </div>

                            {{-- Feature List --}}
                            <ul class="space-y-4 flex-grow">
                                @foreach ($role['features'] as $feature)
                                    <li class="flex items-start text-sm font-medium text-slate-600">
                                        <span
                                            class="mr-3 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-{{ $role['color'] }}-100 text-{{ $role['color'] }}-600">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>

                            {{-- Action Button (Optional hint) --}}
                            <div
                                class="mt-8 pt-6 border-t border-slate-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span
                                    class="text-xs font-black text-{{ $role['color'] }}-600 uppercase tracking-tighter">View
                                    Interface →</span>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>

        {{-- Contact CTA --}}
        <section class="py-20">
            <div class="max-w-5xl mx-auto px-6 lg:px-8">
                <div class="rounded-[2rem] bg-sky-50 border border-sky-100 p-8 md:p-12 text-center">

                    <div class="text-xs font-black uppercase tracking-[0.3em] text-sky-600">
                        Contact Us
                    </div>

                    <h2 class="mt-3 text-3xl sm:text-4xl font-black tracking-tight">
                        有兴趣使用这套系统？
                    </h2>

                    <p class="mt-4 text-slate-600 max-w-2xl mx-auto leading-relaxed">
                        如果你正在寻找一套完整的叫车 / 派车管理系统，
                        欢迎联系我们了解更多详情、系统演示或定制开发方案。
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/601156898898" target="_blank"
                            class="inline-flex items-center justify-center rounded-2xl bg-green-500 px-6 py-3.5 text-sm font-black text-white hover:bg-green-600 transition">

                            WhatsApp 咨询
                        </a>

                        {{-- Email --}}
                        <a href="mailto:cs.extechstudio@gmail.com"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-black text-slate-700 hover:bg-slate-50 transition">

                            Email 联系
                        </a>

                    </div>

                </div>
            </div>
        </section>

        @include('partials.landing-footer')

        {{-- Pricing Popup --}}
        <div x-show="pricing" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full p-10 relative">

                {{-- close --}}
                <button @click="pricing=false"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-2xl">
                    ✕
                </button>

                <div class="grid md:grid-cols-2 gap-10">

                    {{-- Plan 1 --}}
                    <div>
                        <h3 class="text-xl font-black text-slate-900">
                            月租方案
                        </h3>

                        <div class="text-4xl font-black text-sky-500 mt-4">
                            RM xxxx / 月
                        </div>

                        <ul class="mt-6 space-y-3 text-sm text-slate-600">
                            <li>✔ 系统使用权限</li>
                            <li>✔ 系统更新 (System Updates)</li>
                            <li>✔ 技术支持 (Technical Support)</li>
                            <li>✔ 系统维护 (Maintenance Included)</li>
                            <li>✔ Bug 修复与优化</li>
                            <li class="text-red-400">✘ 无系统源码</li>
                        </ul>

                        <button class="mt-6 w-full rounded-xl bg-sky-500 text-white py-3 font-bold">
                            购买
                        </button>
                    </div>

                    {{-- Plan 2 --}}
                    <div class="border-l pl-10">
                        <h3 class="text-xl font-black text-slate-900">
                            买断授权
                        </h3>

                        <div class="text-4xl font-black text-sky-500 mt-4">
                            RM xxxxx
                        </div>

                        <ul class="mt-6 space-y-3 text-sm text-slate-600">
                            <li>✔ 一次性购买系统</li>
                            <li>✔ 完整系统源码</li>
                            <li>✔ 私有服务器部署</li>
                            <li>✔ 商业使用授权</li>
                            <li class="text-red-400">✘ 不包含系统维护</li>
                            <li class="text-red-400">✘ 不包含技术支持</li>
                        </ul>

                        <p class="text-xs text-slate-400 mt-4">
                            如需系统维护或技术支持，可额外购买 Maintenance Plan。
                        </p>

                        <button class="mt-6 w-full rounded-xl bg-sky-600 text-white py-3 font-bold">
                            购买
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </div>

</body>

</html>
