@extends('layouts.driver-app')

@section('title', '司机仪表板')

@section('content')

    @php
        $currentOrder = \App\Models\Order::where('driver_id', auth()->id())
            ->whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip'])
            ->latest()
            ->first();

        $me = auth()->user();
        $isOnline = (bool) ($me->is_online ?? false);
    @endphp

    <div class="space-y-8">

        {{-- 🔵 状态栏 --}}
        <div
            class="relative overflow-hidden rounded-[2.5rem] p-6 text-white
    {{ $currentOrder ? 'bg-indigo-600' : ($isOnline ? 'bg-emerald-600' : 'bg-slate-800') }}
    shadow-[0_18px_50px_rgba(0,0,0,0.18)]">

            {{-- Soft glow blobs --}}
            <div class="pointer-events-none absolute -top-10 -right-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-16 h-56 w-56 rounded-full bg-black/20 blur-3xl"></div>

            <div class="relative z-10 flex items-start justify-between gap-4">

                {{-- Left: Title + Subtitle --}}
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-[0.22em] text-white/75">
                            {{ $currentOrder ? 'Active Trip' : 'Driver Status' }}
                        </span>

                        {{-- Capsule badge --}}
                        <span
                            class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-widest
                    border border-white/15 bg-white/10">
                            <span
                                class="h-1.5 w-1.5 rounded-full
                        {{ $currentOrder ? 'bg-yellow-300 animate-pulse' : ($isOnline ? 'bg-white animate-pulse' : 'bg-white/40') }}"></span>
                            {{ $currentOrder ? '进行中' : ($isOnline ? '已上线' : '已下线') }}
                        </span>
                    </div>

                    <h2 class="mt-2 text-2xl font-black tracking-tight leading-tight">
                        {{ $currentOrder ? '行程进行中' : ($isOnline ? '等待派单中' : '点击上线开始接单') }}
                    </h2>

                    <p class="mt-1 text-xs font-bold text-white/70 leading-relaxed">
                        @if ($currentOrder)
                            请按流程更新状态：出发 → 到达 → 行程中 → 完成
                        @else
                            {{ $isOnline ? '保持在线，经理会把订单派给你。' : '上线后才会收到派单通知。' }}
                        @endif
                    </p>
                </div>

                {{-- Right: Action --}}
                {{-- Right: Action (Super clear) --}}
                <div class="shrink-0 flex flex-col items-end gap-2">
                    {{-- Big status text --}}
                    <div class="text-right">
                        <div class="text-[11px] font-black tracking-widest uppercase text-white/70">
                            接单开关
                        </div>
                        <div class="text-lg font-black">
                            {{ $isOnline ? '在线接单' : '离线休息' }}
                        </div>
                    </div>

                    @if ($currentOrder)
                        <div
                            class="px-4 py-2 rounded-2xl bg-white/10 border border-white/15 text-white/70 text-xs font-black">
                            行程中 · 不能下线
                        </div>
                    @else
                        {{-- Toggle --}}
                        <form method="POST" action="{{ $isOnline ? route('driver.offline') : route('driver.online') }}">
                            @csrf
                            <button type="submit"
                                class="group relative w-[92px] h-[44px] rounded-full border border-white/20
                       {{ $isOnline ? 'bg-white/20' : 'bg-white/10' }}
                       shadow-[0_12px_30px_rgba(0,0,0,0.18)]
                       active:scale-95 transition">
                                {{-- Knob --}}
                                <span
                                    class="absolute top-1 left-1 h-9 w-9 rounded-full bg-white shadow-md transition-all
                    {{ $isOnline ? 'translate-x-[48px]' : 'translate-x-0' }}">
                                </span>

                                {{-- ON / OFF labels --}}
                                <span
                                    class="absolute inset-0 flex items-center justify-between px-3 text-[11px] font-black">
                                    <span class="{{ $isOnline ? 'text-white/80' : 'text-white/50' }}">OFF</span>
                                    <span class="{{ $isOnline ? 'text-white' : 'text-white/50' }}">ON</span>
                                </span>
                            </button>
                        </form>

                        {{-- Clear hint text --}}
                        <div class="text-[11px] font-bold text-white/70">
                            {{ $isOnline ? '要休息就关掉开关' : '把开关打开开始接单' }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Heartbeat：上线时每次进来都刷新 last_active_at --}}
            @if ($isOnline)
                @php
                    $last = optional($me->last_active_at)->timestamp ?? 0;
                    if (now()->timestamp - $last > 60) {
                        $me->forceFill(['last_active_at' => now()])->save();
                    }
                @endphp
            @endif
        </div>


        {{-- 🚗 当前订单卡片 --}}
        @if ($currentOrder)

            <div class="bg-white rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden">

                {{-- Header --}}
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase text-gray-400 font-bold tracking-widest">
                                当前订单
                            </div>
                            <div class="text-xl font-extrabold text-gray-900 mt-1">
                                订单 #{{ $currentOrder->id }}
                            </div>
                        </div>

                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold uppercase
    @switch($currentOrder->status)
        @case('assigned') bg-blue-100 text-blue-700 @break
        @case('on_the_way') bg-amber-100 text-amber-700 @break
        @case('arrived') bg-purple-100 text-purple-700 @break
        @case('in_trip') bg-indigo-100 text-indigo-700 @break
        @case('completed') bg-emerald-100 text-emerald-700 @break
    @endswitch">
                            {{-- 这里把状态转成华文显示 --}}
                            @switch($currentOrder->status)
                                @case('assigned')
                                    已派单
                                @break

                                @case('on_the_way')
                                    前往中
                                @break

                                @case('arrived')
                                    已到达
                                @break

                                @case('in_trip')
                                    行程中
                                @break

                                @case('completed')
                                    已完成
                                @break

                                @default
                                    {{ str_replace('_', ' ', $currentOrder->status) }}
                            @endswitch
                        </span>
                    </div>
                </div>

                {{-- Route Info --}}
                <div class="p-6 space-y-5">

                    <div>
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                            上车地点
                        </div>
                        <div class="text-lg font-extrabold text-gray-900 mt-1">
                            {{ $currentOrder->pickup }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                            下车地点
                        </div>
                        <div class="text-lg font-extrabold text-gray-900 mt-1">
                            {{ $currentOrder->dropoff }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-500 font-semibold">
                        <div>付款方式：{{ strtoupper($currentOrder->payment_type) }}</div>
                        <div>
                            {{ $currentOrder->schedule_type === 'scheduled' ? '预约单' : '即时单' }}
                        </div>
                    </div>

                </div>

                {{-- ACTION BUTTONS --}}
                <div class="p-6 bg-gray-50 space-y-3">

                    @if ($currentOrder->status === 'assigned')
                        <form method="POST" action="{{ route('driver.orders.status', $currentOrder) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="on_the_way">

                            <button class="w-full h-14 rounded-2xl bg-indigo-600 text-white font-extrabold text-lg">
                                开始出发
                            </button>
                        </form>
                    @endif

                    @if ($currentOrder->status === 'on_the_way')
                        <form method="POST" action="{{ route('driver.orders.status', $currentOrder) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="arrived">

                            <button class="w-full h-14 rounded-2xl bg-amber-500 text-white font-extrabold text-lg">
                                已到达上车点
                            </button>
                        </form>
                    @endif

                    @if ($currentOrder->status === 'arrived')
                        <form method="POST" action="{{ route('driver.orders.status', $currentOrder) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="in_trip">

                            <button class="w-full h-14 rounded-2xl bg-blue-600 text-white font-extrabold text-lg">
                                开始行程
                            </button>
                        </form>
                    @endif

                    @if ($currentOrder->status === 'in_trip')
                        <form method="POST" action="{{ route('driver.orders.status', $currentOrder) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">

                            <button class="w-full h-14 rounded-2xl bg-emerald-600 text-white font-extrabold text-lg">
                                完成行程
                            </button>
                        </form>
                    @endif

                </div>

            </div>
        @else
            {{-- 💤 没有订单 --}}
            <div class="bg-white rounded-3xl p-10 text-center shadow-sm border border-gray-100">
                <div class="text-gray-400 font-bold uppercase text-xs tracking-widest">
                    暂无进行中订单
                </div>
                <div class="text-xl font-extrabold text-gray-900 mt-3">
                    等待经理派单中
                </div>
                <div class="text-sm text-gray-500 mt-2">
                    请保持上线并随时待命 🚗
                </div>
            </div>

        @endif

    </div>

@endsection
