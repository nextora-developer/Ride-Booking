@extends('layouts.driver-app')

@section('title', '司机仪表板')

@section('content')

    @php
        $currentOrder = \App\Models\Order::where('driver_id', auth()->id())
            ->whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip'])
            ->latest()
            ->first();
    @endphp

    <div class="space-y-8">

        {{-- 🔵 状态栏 --}}
        <div class="rounded-3xl p-6 text-white
        {{ $currentOrder ? 'bg-indigo-600' : 'bg-emerald-600' }}">

            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase tracking-widest opacity-80 font-bold">
                        {{ $currentOrder ? '进行中订单' : '你已上线' }}
                    </div>
                    <div class="text-2xl font-extrabold mt-1">
                        {{ $currentOrder ? '行程进行中' : '等待派单中' }}
                    </div>
                </div>

                <div class="h-4 w-4 rounded-full
                    {{ $currentOrder ? 'bg-yellow-300 animate-pulse' : 'bg-white animate-pulse' }}">
                </div>
            </div>
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
                                @case('assigned') 已派单 @break
                                @case('on_the_way') 前往中 @break
                                @case('arrived') 已到达 @break
                                @case('in_trip') 行程中 @break
                                @case('completed') 已完成 @break
                                @default {{ str_replace('_', ' ', $currentOrder->status) }}
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