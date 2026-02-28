@extends('layouts.driver-app')

@section('title', '司机仪表板')

@php
    $currentOrder = \App\Models\Order::where('driver_id', auth()->id())
        ->whereIn('status', ['assigned', 'on_the_way', 'arrived', 'in_trip'])
        ->latest()
        ->first();

    $me = auth()->user();
    $isOnline = (bool) ($me->is_online ?? false);
@endphp

@section('header')
    {{-- App Style Top Bar (Driver) --}}
    <div class="px-4 py-4 flex items-center justify-between">
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">司机控制台</h1>

                {{-- Status badge --}}
                <span
                    class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest
                    {{ $currentOrder ? 'bg-indigo-600 text-white' : ($isOnline ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600') }}">
                    {{ $currentOrder ? 'ON JOB' : ($isOnline ? 'ONLINE' : 'OFFLINE') }}
                </span>
            </div>

            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none truncate">
                Driver Dashboard
            </span>
        </div>

        <div class="flex items-center gap-3">
            {{-- Refresh --}}
            <button onclick="window.location.reload()"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm hover:bg-slate-50">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </button>

            {{-- Avatar --}}
            <div
                class="h-10 w-10 rounded-2xl bg-slate-800 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-slate-200">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </div>
@endsection

@section('content')

    @php
        // ✅ 先给默认值（currentOrder=null 也不会报错）
        $customerName = '顾客';
        $rawPhone = null;
        $phoneDigits = null;
        $amount = 0.0;
        $pax = 1;
        $note = null;

        $pickup = '';
        $dropoff = '';
        $navPickupUrl = '#';
        $navDropoffUrl = '#';

        if ($currentOrder) {
            $customerName = $currentOrder->customer_name ?? (optional($currentOrder->user)->name ?? '顾客');

            $rawPhone = $currentOrder->customer_phone ?? (optional($currentOrder->user)->phone ?? null);

            $phoneDigits = $rawPhone ? preg_replace('/\D+/', '', $rawPhone) : null;
            if ($phoneDigits && str_starts_with($phoneDigits, '0')) {
                $phoneDigits = '60' . ltrim($phoneDigits, '0');
            }

            $amount = (float) ($currentOrder->amount ?? ($currentOrder->total ?? 0));
            $pax = (int) ($currentOrder->pax ?? 1);
            $note = $currentOrder->note ?? null;

            $pickup = $currentOrder->pickup ?? '';
            $dropoff = $currentOrder->dropoff ?? '';
            $navPickupUrl = $pickup ? 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($pickup) : '#';
            $navDropoffUrl = $dropoff
                ? 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($dropoff)
                : '#';
        }
    @endphp

    <div class="space-y-6">

        {{-- 🔵 顶部状态胶囊 --}}
        <div
            class="relative overflow-hidden rounded-[2rem] p-6 text-white transition-all duration-300
        {{ $currentOrder ? 'bg-indigo-600' : ($isOnline ? 'bg-emerald-500' : 'bg-slate-800') }}
        shadow-[0_14px_35px_rgba(15,23,42,0.12)]">

            <div class="relative z-10 flex items-center justify-between">
                {{-- 左侧：状态文本 --}}
                <div>
                    <div class="flex items-center gap-2 opacity-80">
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-white {{ $isOnline || $currentOrder ? 'animate-pulse' : 'opacity-40' }}"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest">
                            {{ $currentOrder ? '行程进行中' : ($isOnline ? '系统在线' : '离线休息') }}
                        </span>
                    </div>
                    <h2 class="mt-1 text-2xl font-black tracking-tight">
                        {{ $currentOrder ? '请完成当前订单' : ($isOnline ? '正在等待订单...' : '已停止接单') }}
                    </h2>
                </div>

                {{-- 右侧：极简开关 --}}
                <div class="flex flex-col items-end">
                    @if (!$currentOrder)
                        <form method="POST" action="{{ $isOnline ? route('driver.offline') : route('driver.online') }}">
                            @csrf
                            <button type="submit"
                                class="relative w-14 h-8 bg-white/20 rounded-full transition-colors border border-white/20 active:scale-95">
                                <div
                                    class="absolute top-1 left-1 h-6 w-6 rounded-full bg-white shadow-sm transition-transform duration-200
                                {{ $isOnline ? 'translate-x-6' : 'translate-x-0' }}">
                                </div>
                            </button>
                        </form>
                    @else
                        <div
                            class="h-8 px-3 rounded-lg bg-black/25 border border-white/10 flex items-center justify-center">
                            <span class="text-[10px] font-black uppercase tracking-widest">执行中</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 🚗 订单核心操作区 --}}
        @if ($currentOrder)
            <div
                class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden
                    shadow-[0_12px_30px_rgba(15,23,42,0.06)]">

                {{-- ✅ Top Bar --}}
                <div class="px-5 py-4 flex items-center justify-between border-b border-slate-200 bg-slate-100/60">
                    <div class="min-w-0">
                        <div class="text-base font-black text-slate-900 truncate">
                            订单 #{{ $currentOrder->id }}
                        </div>
                    </div>

                    <div class="shrink-0">
                        @php
                            $statusLabel = match ($currentOrder->status) {
                                'assigned' => '待出发',
                                'on_the_way' => '前往接送',
                                'arrived' => '已达起点',
                                'in_trip' => '行程中',
                                default => $currentOrder->status,
                            };

                            $statusClass = match ($currentOrder->status) {
                                'assigned' => 'bg-slate-900 text-white',
                                'on_the_way' => 'bg-amber-500 text-white',
                                'arrived' => 'bg-blue-600 text-white',
                                'in_trip' => 'bg-indigo-600 text-white',
                                default => 'bg-slate-200 text-slate-700',
                            };
                        @endphp

                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-black {{ $statusClass }}">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-white/80
                            {{ in_array($currentOrder->status, ['assigned', 'on_the_way', 'arrived', 'in_trip']) ? 'animate-pulse' : '' }}">
                            </span>
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                {{-- ✅ Customer Row --}}
                <div class="px-5 py-4 border-b border-slate-200">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="h-11 w-11 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-lg">
                                👤
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-black text-slate-900 truncate">{{ $customerName }}</div>
                                <div class="text-[11px] font-bold text-slate-600 truncate">
                                    {{ $rawPhone ?? '— 没有电话 —' }}
                                </div>
                            </div>
                        </div>

                        {{-- Call / WhatsApp --}}
                        <div class="shrink-0 flex gap-2">
                            <a href="{{ $phoneDigits ? 'tel:+' . $phoneDigits : '#' }}"
                                class="h-10 w-10 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-center
                            active:scale-95 transition {{ $phoneDigits ? '' : 'opacity-40 pointer-events-none' }}">
                                📞
                            </a>
                            <a href="{{ $phoneDigits ? 'https://wa.me/' . $phoneDigits : '#' }}" target="_blank"
                                class="h-10 w-10 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-center
                            active:scale-95 transition {{ $phoneDigits ? '' : 'opacity-40 pointer-events-none' }}">
                                💬
                            </a>
                        </div>
                    </div>

                    {{-- ✅ App Info Pills --}}
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">

                        <div class="rounded-2xl bg-white border border-slate-200 px-4 py-4 text-center shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase tracking-widest">乘客人数</div>
                            <div class="text-lg font-black text-slate-900 mt-1 leading-none">{{ $pax }}</div>
                        </div>

                        <div class="rounded-2xl bg-white border border-slate-200 px-4 py-4 text-center shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase tracking-widest">车费</div>
                            <div class="text-lg font-black text-slate-900 mt-1 leading-none">
                                RM {{ number_format($amount, 0) }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white border border-slate-200 px-4 py-4 text-center shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase tracking-widest">付款方式</div>
                            <div class="text-sm font-black text-slate-900 mt-1 leading-none truncate">
                                {{ strtoupper($currentOrder->payment_type ?? '-') }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white border border-slate-200 px-4 py-4 text-center shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase tracking-widest">类型</div>
                            <div class="text-sm font-black text-slate-900 mt-1 leading-none">
                                {{ ($currentOrder->schedule_type ?? '') === 'scheduled' ? '预约单' : '即时单' }}
                            </div>
                        </div>

                    </div>

                    {{-- ✅ Note --}}
                    @if (!empty($note))
                        <div class="mt-4 rounded-2xl bg-amber-100/60 border border-amber-200 p-4">
                            <div class="flex items-start gap-3">
                                <div class="text-xl">📝</div>
                                <div class="min-w-0">
                                    <div class="text-[10px] font-black text-amber-700 uppercase tracking-widest">乘客备注</div>
                                    <div class="text-sm font-bold text-amber-900 mt-1 leading-relaxed break-words">
                                        {{ $note }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ✅ 收款提示 --}}
                    @php
                        $pay = strtolower((string) ($currentOrder->payment_type ?? ''));
                        $collectHint = null;

                        if (in_array($pay, ['cash', '现金'])) {
                            $collectHint = '⚠️ 需要收现金：RM ' . number_format($amount, 2);
                        } elseif (in_array($pay, ['credit', '挂单', 'wallet'])) {
                            $collectHint = '✅ 系统结算：无需向顾客收款';
                        } elseif (in_array($pay, ['transfer', '转账', 'bank'])) {
                            $collectHint = '⚠️ 转账单：请确认顾客已转账';
                        }
                    @endphp

                    @if ($collectHint)
                        <div class="mt-4 rounded-2xl bg-slate-900 text-white px-4 py-3 shadow-sm">
                            <div class="text-xs font-black tracking-tight">{{ $collectHint }}</div>
                        </div>
                    @endif
                </div>

                {{-- ✅ Route Cards --}}
                <div class="px-5 py-5 space-y-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest">上车地点</div>
                                </div>
                                <div class="text-sm font-black text-slate-900 mt-2 leading-snug break-words">
                                    {{ $pickup }}
                                </div>
                            </div>

                            <div class="shrink-0 flex gap-2">
                                <button type="button" onclick="copyText(@js($pickup), this)"
                                    class="h-9 px-3 rounded-xl bg-slate-100 border border-slate-200 text-[11px] font-black text-slate-700 active:scale-95 transition">
                                    复制
                                </button>
                                <a href="{{ $navPickupUrl }}" target="_blank"
                                    class="h-9 px-3 rounded-xl bg-slate-900 text-white text-[11px] font-black flex items-center active:scale-95 transition">
                                    导航
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest">下车地点</div>
                                </div>
                                <div class="text-sm font-black text-slate-900 mt-2 leading-snug break-words">
                                    {{ $dropoff }}
                                </div>
                            </div>

                            <div class="shrink-0 flex gap-2">
                                <button type="button" onclick="copyText(@js($dropoff), this)"
                                    class="h-9 px-3 rounded-xl bg-slate-100 border border-slate-200 text-[11px] font-black text-slate-700 active:scale-95 transition">
                                    复制
                                </button>
                                <a href="{{ $navDropoffUrl }}" target="_blank"
                                    class="h-9 px-3 rounded-xl bg-slate-900 text-white text-[11px] font-black flex items-center active:scale-95 transition">
                                    导航
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ Action Button --}}
                <div class="p-5 bg-slate-100/60 border-t border-slate-200">
                    <form method="POST" action="{{ route('driver.orders.status', $currentOrder) }}"> @csrf
                        @method('PATCH') @php $nextStatus = [ 'assigned' => [ 'val' => 'on_the_way', 'label' => '开始出发', 'color' => 'bg-indigo-600', ], 'on_the_way' => [ 'val' => 'arrived', 'label' => '已到达上车点', 'color' => 'bg-amber-500', ], 'arrived' => ['val' => 'in_trip', 'label' => '开始行程', 'color' => 'bg-blue-600'], 'in_trip' => [ 'val' => 'completed', 'label' => '完成行程', 'color' => 'bg-emerald-600', ], ][$currentOrder->status] ?? null; @endphp @if ($nextStatus)
                            <input type="hidden" name="status" value="{{ $nextStatus['val'] }}"> <button
                                class="w-full h-16 rounded-[1.5rem] {{ $nextStatus['color'] }} text-white font-black text-lg shadow-sm active:scale-95 transition flex items-center justify-center gap-3">
                                <span>{{ $nextStatus['label'] }}</span> <svg class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg> </button>
                        @endif
                    </form>
                </div>

            </div>
        @else
            {{-- 💤 闲置状态 --}}
            <div
                class="py-12 bg-white rounded-[2.5rem] border border-dashed border-slate-300 flex flex-col items-center justify-center text-center shadow-sm">
                <div
                    class="h-20 w-20 bg-slate-100 rounded-full flex items-center justify-center text-4xl mb-4 animate-bounce">
                    ☕</div>
                <h3 class="text-lg font-black text-slate-800">暂无新单</h3>
                <p class="text-xs text-slate-500 font-bold mt-1 px-10">
                    {{ $isOnline ? '雷达正在全速搜索附近订单，请保持网络通畅。' : '你现在处于离线状态，上线后经理才能为你派单。' }}
                </p>
            </div>
        @endif

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

    <script>
        function copyText(text, btn) {
            // 1) 优先用 clipboard（HTTPS / localhost 通常 OK）
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    if (btn) {
                        const old = btn.innerText;
                        btn.innerText = '✅';
                        setTimeout(() => btn.innerText = old, 800);
                    }
                });
                return;
            }

            // 2) fallback：HTTP 也能用
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            ta.style.top = '-9999px';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();

            try {
                document.execCommand('copy');
                if (btn) {
                    const old = btn.innerText;
                    btn.innerText = '✅';
                    setTimeout(() => btn.innerText = old, 800);
                }
            } catch (e) {
                alert('复制失败，请长按手动复制');
            } finally {
                document.body.removeChild(ta);
            }
        }
    </script>
@endsection
