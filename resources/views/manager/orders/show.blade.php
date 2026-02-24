@extends('layouts.manager-app')

@section('title', 'Order Details')

@section('header')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Order #{{ $order->id }}
            </h1>
            <p class="text-slate-500 mt-1">查看订单并派单给司机（现金 / 挂单 / 转账）</p>
        </div>

        <a href="{{ route('manager.orders.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
            Back
        </a>
    </div>
@endsection

@section('content')
    @php
        $badge = match ($order->status) {
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
            'assigned' => 'bg-blue-50 text-blue-700 border-blue-100',
            'on_the_way', 'arrived' => 'bg-amber-50 text-amber-700 border-amber-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };

        $when =
            $order->schedule_type === 'scheduled' && $order->scheduled_at ? $order->scheduled_at : $order->created_at;

        $payLabel = fn($v) => match ($v) {
            'cash' => '现金',
            'credit' => '挂单',
            'transfer' => '转账',
            default => strtoupper($v ?? 'cash'),
        };

        $pt = old('payment_type', $order->payment_type ?? 'cash');
        $payOptions = [
            'cash' => '现金',
            'credit' => '挂单',
            'transfer' => '转账',
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">

        {{-- LEFT: Order info --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white border border-gray-100 rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div class="min-w-0">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest">Route</div>

                        <div class="mt-3 flex items-start gap-3">
                            <div class="flex flex-col items-center gap-1 shrink-0 mt-1">
                                <div class="h-2 w-2 rounded-full bg-slate-300"></div>
                                <div class="h-6 w-px bg-slate-200"></div>
                                <div class="h-2 w-2 rounded-full bg-slate-900"></div>
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-slate-500 truncate">Pickup</div>
                                <div class="text-lg font-extrabold text-slate-900 truncate">{{ $order->pickup }}</div>

                                <div class="mt-3 text-sm font-bold text-slate-500 truncate">Dropoff</div>
                                <div class="text-lg font-extrabold text-slate-900 truncate">{{ $order->dropoff }}</div>
                            </div>
                        </div>
                    </div>

                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black border {{ $badge }} uppercase tracking-widest">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="rounded-2xl border border-gray-100 bg-slate-50/40 p-4">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Time</div>
                        <div class="mt-1 font-extrabold text-slate-900">{{ $when->format('d M Y, h:i A') }}</div>
                        <div class="text-xs text-slate-400 mt-1 font-semibold">
                            {{ $order->schedule_type === 'scheduled' ? 'Scheduled' : 'Instant' }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-slate-50/40 p-4">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Shift</div>
                        <div class="mt-1 font-extrabold text-slate-900 capitalize">{{ $order->shift ?? '-' }}</div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-slate-50/40 p-4">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Payment Type</div>
                        <div class="mt-1 font-extrabold text-slate-900">
                            {{ $payLabel($order->payment_type) }}
                            @if ($order->payment_status)
                                <span class="text-xs text-slate-400 font-black">•
                                    {{ strtoupper($order->payment_status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-slate-50/40 p-4">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Assigned Driver</div>
                        <div class="mt-1 font-extrabold text-slate-900">
                            {{ $order->driver?->name ?? 'Not Assigned' }}
                        </div>
                        @if ($order->assigned_at)
                            <div class="text-xs text-slate-400 mt-1 font-semibold">
                                {{ $order->assigned_at->format('d M, h:i A') }}
                            </div>
                        @endif
                    </div>

                    @if ($order->note)
                        <div class="sm:col-span-2 rounded-2xl border border-gray-100 bg-white p-4">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Note</div>
                            <div class="mt-1 font-bold text-slate-900">{{ $order->note }}</div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT: Assign panel --}}
        <div class="space-y-6">

            <div class="bg-white border border-gray-100 rounded-[2rem] p-7 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Assign Driver</h3>
                        <p class="text-sm text-slate-500 mt-1">选择司机 + 付款方式</p>
                    </div>

                    {{-- 切换司机范围：同班/全部 --}}
                    <a href="{{ route('manager.orders.show', [$order, 'drivers' => request('drivers') === 'all' ? null : 'all']) }}"
                        class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900">
                        {{ request('drivers') === 'all' ? 'Same Shift' : 'All Drivers' }}
                    </a>
                </div>

                {{-- 如果不是 pending：提示不能派 --}}
                @if ($order->status !== 'pending')
                    <div class="mt-4 rounded-2xl border border-amber-100 bg-amber-50 p-4 text-amber-800 text-sm">
                        <div class="font-extrabold">Cannot assign</div>
                        <div class="mt-1 opacity-80">Only <span class="font-black">pending</span> orders can be assigned.
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('manager.orders.assign', $order) }}" class="mt-5 space-y-4">
                    @csrf
                    @method('PATCH')

                    {{-- Driver --}}
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver</label>
                        <select name="driver_id"
                            class="mt-1 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 focus:border-slate-900 focus:ring-4 focus:ring-slate-900/5"
                            {{ $order->status !== 'pending' ? 'disabled' : '' }}>
                            <option value="">-- Select Driver --</option>
                            @foreach ($drivers as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('driver_id', $order->driver_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }} {{ $d->shift ? '· ' . ucfirst($d->shift) : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('driver_id')
                            <p class="text-xs text-rose-600 font-bold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Type (radio 必中版) --}}
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Payment Type</label>

                        @php
                            $pt = old('payment_type', $order->payment_type ?? 'cash');
                        @endphp

                        <div class="mt-2 grid grid-cols-3 gap-2">
                            {{-- CASH --}}
                            <label class="cursor-pointer select-none">
                                <input type="radio" name="payment_type" value="cash" class="peer sr-only"
                                    {{ $pt === 'cash' ? 'checked' : '' }} />
                                <div
                                    class="h-11 rounded-2xl border px-3 flex items-center justify-center text-sm font-extrabold transition
                   bg-white text-slate-700 border-gray-200 hover:bg-slate-50
                   peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900">
                                    现金
                                </div>
                            </label>

                            {{-- CREDIT --}}
                            <label class="cursor-pointer select-none">
                                <input type="radio" name="payment_type" value="credit" class="peer sr-only"
                                    {{ $pt === 'credit' ? 'checked' : '' }} />
                                <div
                                    class="h-11 rounded-2xl border px-3 flex items-center justify-center text-sm font-extrabold transition
                   bg-white text-slate-700 border-gray-200 hover:bg-slate-50
                   peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900">
                                    挂单
                                </div>
                            </label>

                            {{-- TRANSFER --}}
                            <label class="cursor-pointer select-none">
                                <input type="radio" name="payment_type" value="transfer" class="peer sr-only"
                                    {{ $pt === 'transfer' ? 'checked' : '' }} />
                                <div
                                    class="h-11 rounded-2xl border px-3 flex items-center justify-center text-sm font-extrabold transition
                   bg-white text-slate-700 border-gray-200 hover:bg-slate-50
                   peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900">
                                    转账
                                </div>
                            </label>
                        </div>

                        @error('payment_type')
                            <p class="text-xs text-rose-600 font-bold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        class="w-full h-11 rounded-2xl bg-slate-900 text-white font-extrabold hover:bg-slate-800 transition disabled:opacity-60 disabled:cursor-not-allowed"
                        {{ $order->status !== 'pending' ? 'disabled' : '' }}>
                        Assign Now
                    </button>

                    <p class="text-xs text-slate-400 font-semibold leading-relaxed">
                        * 派单后将自动设置为 <span class="font-black text-slate-700">assigned</span>，并记录 assigned_at。
                    </p>
                </form>
            </div>

            {{-- Assigned driver card (不使用 phone，避免爆) --}}
            @if ($order->driver)
                <div class="bg-slate-900 text-white rounded-[2rem] p-7">
                    <div class="text-xs font-black uppercase tracking-widest opacity-70">Assigned Driver</div>
                    <div class="mt-2 text-xl font-extrabold">{{ $order->driver->name }}</div>
                    <p class="mt-2 text-sm opacity-80">
                        Shift: <span class="font-bold">{{ ucfirst($order->driver->shift ?? '-') }}</span>
                    </p>
                </div>
            @endif

        </div>
    </div>
@endsection
