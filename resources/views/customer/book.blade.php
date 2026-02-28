@extends('layouts.customer-app')

@section('title', '创建预约')

@section('header')
    <div class="relative px-2">
        {{-- Mobile: App Header --}}
        <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">
            <div class="absolute left-0 top-1/2 -translate-y-1/2">
                <a href="{{ route('customer.home') }}"
                    class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </div>

            <div class="text-center">
                <h1 class="text-lg font-black text-slate-800 leading-none">创建预约</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Create Booking</p>
            </div>
        </div>

        {{-- Desktop: Breadcrumb-style Header --}}
        <div class="hidden md:flex items-center justify-between py-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">创建新预约</h1>
                <p class="text-slate-500 font-bold mt-1 text-sm flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                    请完善行程资料以获取精准服务
                </p>
            </div>
            <a href="{{ route('customer.home') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 text-sm font-black hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                返回首页
            </a>
        </div>
    </div>
@endsection

@section('content')
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @php
        $services = [
            [
                'key' => 'pickup_dropoff',
                'label' => '接送',
                'desc' => '点对点',
                'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
            ],
            [
                'key' => 'charter',
                'label' => '包车',
                'desc' => '按小时',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'key' => 'designated_driver',
                'label' => '代驾',
                'desc' => '为您开车',
                'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            ],
            [
                'key' => 'purchase',
                'label' => '代购',
                'desc' => '跑腿买菜',
                'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
            ],
            [
                'key' => 'big_car',
                'label' => '大车',
                'desc' => 'MPV/Van',
                'icon' =>
                    'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            ],
            [
                'key' => 'driver_only',
                'label' => '司机',
                'desc' => '长短期',
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start pb-10">

        {{-- Left: Main Form --}}
        <div class="lg:col-span-8 space-y-6">
            <form method="POST" action="{{ route('customer.book.store') }}" x-data="{ scheduleType: '{{ old('schedule_type', 'now') }}' }"
                class="relative bg-white/85 border border-slate-300/60 rounded-[2.5rem] p-6 sm:p-10
                       shadow-[0_22px_60px_rgba(15,23,42,0.10)] backdrop-blur">
                @csrf

                {{-- Card depth --}}
                <div
                    class="absolute inset-0 rounded-[2.5rem] pointer-events-none bg-gradient-to-b from-white/80 via-white/55 to-slate-50/70">
                </div>
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none ring-1 ring-slate-900/5"></div>

                <div class="relative">

                    {{-- Section 1: Service Selection --}}
                    <div class="mb-12">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-slate-900 text-white text-[10px] font-black tracking-widest">
                                    STEP 01
                                </span>
                                <label class="text-[11px] font-black text-slate-800 uppercase tracking-[0.2em]">
                                    选择服务类别
                                </label>
                            </div>
                            <span class="h-px flex-1 bg-slate-200/70 ml-4"></span>
                        </div>

                        {{-- Horizontal Scroll for Mobile --}}
                        <div class="md:hidden -mx-6 px-6 overflow-x-auto no-scrollbar snap-x touch-pan-x">
                            <div class="flex gap-4 w-max pb-4">
                                @foreach ($services as $s)
                                    <label class="relative cursor-pointer snap-center">
                                        <input type="radio" name="service_type" value="{{ $s['key'] }}"
                                            class="peer sr-only" required @checked(old('service_type') === $s['key'])>

                                        <div
                                            class="w-32 rounded-[2rem] border border-slate-200 bg-white/75 p-5 text-center
                                                   shadow-[0_8px_20px_rgba(15,23,42,0.06)]
                                                   transition-all duration-300
                                                   peer-checked:bg-slate-900 peer-checked:border-slate-900
                                                   peer-checked:shadow-[0_14px_30px_rgba(15,23,42,0.15)]
                                                   peer-checked:text-white">
                                            <div
                                                class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl
                                                       bg-slate-50 text-slate-900 shadow-sm border border-slate-200
                                                       peer-checked:bg-white/15 peer-checked:text-white peer-checked:border-white/10 transition-colors">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="{{ $s['icon'] }}" />
                                                </svg>
                                            </div>

                                            <span class="text-sm font-black block leading-tight">{{ $s['label'] }}</span>
                                            <span
                                                class="text-[10px] font-bold text-slate-400 block mt-1 peer-checked:text-white/70">
                                                {{ $s['desc'] }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Grid for Desktop --}}
                        <div class="hidden md:grid grid-cols-3 gap-5">
                            @foreach ($services as $s)
                                <label class="group relative cursor-pointer">
                                    <input type="radio" name="service_type" value="{{ $s['key'] }}"
                                        class="peer sr-only" required @checked(old('service_type') === $s['key'])>

                                    <div
                                        class="h-full flex items-center gap-4 p-5 rounded-3xl
                                               border border-slate-200 bg-white/70
                                               shadow-[0_14px_35px_rgba(15,23,42,0.08)]
                                               transition-all duration-300
                                               hover:bg-white hover:border-slate-300
                                               peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white
                                               peer-checked:shadow-[0_22px_55px_rgba(15,23,42,0.25)]">
                                        <div
                                            class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-2xl
                                                   bg-slate-50 shadow-sm border border-slate-200 text-slate-900
                                                   peer-checked:bg-white/10 peer-checked:text-white peer-checked:border-white/10 transition-all">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="{{ $s['icon'] }}" />
                                            </svg>
                                        </div>

                                        <div>
                                            <span class="text-sm font-black block">{{ $s['label'] }}</span>
                                            <span
                                                class="text-[10px] font-bold text-slate-600 peer-checked:text-white/70 block">
                                                {{ $s['desc'] }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Section 2: Route Details --}}
                    <div class="mb-12">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-slate-900 text-white text-[10px] font-black tracking-widest">
                                    STEP 02
                                </span>
                                <label class="text-[11px] font-black text-slate-800 uppercase tracking-[0.2em]">
                                    行程资料
                                </label>
                            </div>
                            <span class="h-px flex-1 bg-slate-200/70 ml-4"></span>
                        </div>

                        <div class="relative space-y-4">
                            <div
                                class="absolute left-[22px] top-10 bottom-10 w-0.5 bg-gradient-to-b from-indigo-500 via-slate-300 to-emerald-500 hidden sm:block">
                            </div>

                            {{-- Pickup --}}
                            <div class="relative group">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 rounded-full border-4 border-white bg-indigo-500 shadow-sm z-10"></span>
                                <input type="text" name="pickup" value="{{ old('pickup') }}" required
                                    placeholder="从哪里出发？"
                                    class="w-full bg-white/70 border border-slate-200 rounded-[1.5rem] pl-12 pr-4 py-4 text-sm font-bold text-slate-900
                                           shadow-[0_12px_30px_rgba(15,23,42,0.06)]
                                           focus:bg-white focus:ring-4 focus:ring-indigo-100 focus:border-indigo-200 transition-all placeholder:text-slate-400">
                            </div>

                            {{-- Dropoff --}}
                            <div class="relative group">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 rounded-full border-4 border-white bg-emerald-500 shadow-sm z-10"></span>
                                <input type="text" name="dropoff" value="{{ old('dropoff') }}" required
                                    placeholder="要去哪里？"
                                    class="w-full bg-white/70 border border-slate-200 rounded-[1.5rem] pl-12 pr-4 py-4 text-sm font-bold text-slate-900
                                           shadow-[0_12px_30px_rgba(15,23,42,0.06)]
                                           focus:bg-white focus:ring-4 focus:ring-emerald-100 focus:border-emerald-200 transition-all placeholder:text-slate-400">
                            </div>
                        </div>

                        {{-- ✅ PAX --}}
                        <div class="mt-8 group/pax">
                            {{-- Label Section --}}
                            <div class="flex items-center justify-between mb-4 px-1">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="h-5 w-5 rounded-lg bg-indigo-50 flex items-center justify-center border border-indigo-100/50">
                                        <svg class="h-3 w-3 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                    </div>
                                    <label
                                        class="text-[11px] font-black text-slate-500 uppercase tracking-[0.15em]">乘客人数</label>
                                </div>
                                <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-slate-100 text-slate-400">MAX
                                    12</span>
                            </div>

                            {{-- Input & Tips Card --}}
                            <div
                                class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 p-2 rounded-[1.75rem] bg-slate-50/50 border border-slate-100 transition-all hover:border-indigo-100/80 hover:bg-white/50">
                                {{-- Number Input Wrapper --}}
                                <div class="relative flex-shrink-0">
                                    <input type="number" name="pax" min="1" max="12"
                                        value="{{ old('pax', 1) }}" required
                                        class="w-full sm:w-32 bg-white border border-slate-200/60 rounded-[1.25rem] pl-6 pr-10 py-3.5 text-base font-black text-slate-800
                       shadow-[0_4px_12px_rgba(0,0,0,0.02)]
                       focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-200 transition-all outline-none"
                                        placeholder="1">
                                    <span
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 pointer-events-none">位</span>
                                </div>

                                {{-- Tooltip / Notice --}}
                                <div class="flex-1 px-2 py-1">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="h-1 w-1 rounded-full bg-indigo-400"></span>
                                            <p class="text-[11px] font-bold text-slate-500 leading-none">
                                                <span class="text-slate-900">5人以上</span> 建议预约大车 (MPV/Van)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Time Selection --}}
                    <div class="mb-12">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-slate-900 text-white text-[10px] font-black tracking-widest">
                                    STEP 03
                                </span>
                                <label class="text-[11px] font-black text-slate-800 uppercase tracking-[0.2em]">
                                    预约时间
                                </label>
                            </div>
                        </div>

                        <div class="inline-flex rounded-2xl bg-slate-900/5 p-1.5 w-full sm:w-auto border border-slate-200">
                            <button type="button" @click="scheduleType='now'"
                                :class="scheduleType === 'now' ?
                                    'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-600'"
                                class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-xs font-black transition-all duration-300">
                                立即叫车
                            </button>
                            <button type="button" @click="scheduleType='scheduled'"
                                :class="scheduleType === 'scheduled' ?
                                    'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-600'"
                                class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-xs font-black transition-all duration-300">
                                预约时间
                            </button>
                        </div>

                        <input type="hidden" name="schedule_type" :value="scheduleType">

                        <div class="mt-4" x-show="scheduleType === 'scheduled'" x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2">
                            <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                                class="w-full sm:w-72 bg-white/70 border border-slate-200 rounded-[1.25rem] px-5 py-4 text-sm font-bold text-slate-900
                                       shadow-[0_12px_30px_rgba(15,23,42,0.06)]
                                       focus:bg-white focus:ring-4 focus:ring-indigo-100 focus:border-indigo-200 transition-all">
                        </div>
                    </div>

                    {{-- Section 4: Notes --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-slate-900 text-white text-[10px] font-black tracking-widest">
                                STEP 04
                            </span>
                            <label class="block text-[11px] font-black text-slate-800 uppercase tracking-[0.2em]">
                                备注 / 特别要求
                            </label>
                        </div>

                        <textarea name="note" rows="3" placeholder="例如：行李数量、有小孩随行、需要司机帮忙搬运等..."
                            class="w-full bg-white/70 border border-slate-200 rounded-[1.5rem] px-6 py-5 text-sm font-bold text-slate-900
                                   shadow-[0_12px_30px_rgba(15,23,42,0.06)]
                                   focus:bg-white focus:ring-4 focus:ring-slate-200 focus:border-slate-300 transition-all placeholder:text-slate-400">{{ old('note') }}</textarea>
                    </div>

                    {{-- Section 5: Submit --}}
                    <div class="mb-0">
                        <button type="submit"
                            class="group w-full flex items-center justify-center gap-4 py-5 rounded-[1.75rem]
                                   bg-slate-900 text-white font-black text-lg
                                   shadow-[0_22px_55px_rgba(15,23,42,0.30)]
                                   hover:bg-indigo-600 hover:shadow-indigo-200 active:scale-[0.98] transition-all duration-300">
                            <span>确认并提交预约</span>
                            <div
                                class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Right: Info Sidebar --}}
        <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
            <div
                class="relative bg-white/85 border border-slate-300/60 rounded-[2rem] p-8
                        shadow-[0_18px_45px_rgba(15,23,42,0.10)] backdrop-blur">
                {{-- Card depth --}}
                <div
                    class="absolute inset-0 rounded-[2rem] pointer-events-none bg-gradient-to-b from-white/80 via-white/55 to-slate-50/70">
                </div>
                <div class="absolute inset-0 rounded-[2rem] pointer-events-none ring-1 ring-slate-900/5"></div>

                <div class="relative">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-6">账号信息摘要</h3>

                    <div class="space-y-5">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-12 w-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-700 font-black text-xl shadow-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-base font-black text-slate-900">{{ auth()->user()->name }}</p>
                            </div>
                        </div>

                        <div class="pt-5 border-t border-slate-200/70 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-500">账号状态</span>
                                <span
                                    class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase border border-emerald-200">
                                    已验证
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-500">当前步骤</span>
                                <span class="text-xs font-black text-amber-600 uppercase">填写预约资料</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tips --}}
                    <div
                        class="mt-8 p-5 rounded-2xl bg-indigo-600 text-white relative overflow-hidden group shadow-[0_18px_40px_rgba(15,23,42,0.25)]">
                        <div
                            class="absolute -right-4 -top-4 h-24 w-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <div class="relative flex items-start gap-3">
                            <svg class="h-5 w-5 flex-shrink-0 mt-0.5 text-indigo-200" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-black uppercase tracking-widest mb-1">快速贴士</p>
                                <ul class="text-sm font-bold text-indigo-100 space-y-1.5 leading-tight">
                                    <li>• 接机请务必注明航班号</li>
                                    <li>• 代购服务请列出购物清单</li>
                                    <li>• 平台不接受非法运输请求</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Extra --}}
                    <div class="mt-5 text-[11px] font-bold text-slate-500">
                        提醒：若人数较多或行李多，建议选择 <span class="text-slate-900 font-black">大车</span> 并在备注说明。
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
