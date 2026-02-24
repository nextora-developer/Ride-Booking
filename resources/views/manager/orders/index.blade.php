@extends('layouts.manager-app')

@section('title', 'Orders')

@section('header')
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Orders</h1>
            <p class="text-slate-500 font-medium mt-1">派单列表 · 筛选订单并进入详情派单</p>
        </div>

        <a href="{{ route('manager.dashboard') }}"
            class="inline-flex items-center justify-center px-5 py-2 rounded-xl bg-white border border-gray-200 text-slate-700 font-bold hover:bg-slate-50 transition">
            Back to Dashboard
        </a>
    </div>
@endsection

@section('content')
    @php
        $statusConfig = fn($v) => match ($v) {
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
            'assigned' => 'bg-blue-50 text-blue-700 border-blue-100',
            'on_the_way', 'arrived' => 'bg-amber-50 text-amber-700 border-amber-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };

        $serviceLabel = fn($v) => match ($v) {
            'pickup_dropoff' => '接送',
            'charter' => '包车',
            'designated_driver' => '代驾',
            'purchase' => '代购',
            'big_car' => '大车',
            'driver_only' => '司机',
            default => $v,
        };

        $status = request('status', 'all');
        $shift = request('shift', 'all');
        $search = request('search', '');
    @endphp

    {{-- Filters --}}
    <form method="GET" class="mb-6">
        <div class="bg-white border border-gray-100 rounded-[2rem] p-5 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</label>
                    <select name="status"
                        class="mt-1 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 focus:border-slate-900 focus:ring-4 focus:ring-slate-900/5">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ $status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="on_the_way" {{ $status === 'on_the_way' ? 'selected' : '' }}>On The Way</option>
                        <option value="arrived" {{ $status === 'arrived' ? 'selected' : '' }}>Arrived</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Shift</label>
                    <select name="shift"
                        class="mt-1 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 focus:border-slate-900 focus:ring-4 focus:ring-slate-900/5">
                        <option value="all" {{ $shift === 'all' ? 'selected' : '' }}>All</option>
                        <option value="day" {{ $shift === 'day' ? 'selected' : '' }}>Day</option>
                        <option value="night" {{ $shift === 'night' ? 'selected' : '' }}>Night</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Search</label>
                    <input name="search" value="{{ $search }}" placeholder="Search pickup / dropoff / id..."
                        class="mt-1 w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-slate-700
                                  focus:border-slate-900 focus:ring-4 focus:ring-slate-900/5" />
                </div>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
                <div class="text-xs text-slate-500 font-semibold">
                    Showing <span class="font-black text-slate-900">{{ $orders->total() }}</span> orders
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('manager.orders.index') }}"
                        class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                        Reset
                    </a>
                    <button
                        class="px-5 py-2 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- List --}}
    <div class="bg-white border border-gray-100 rounded-[2rem] overflow-hidden shadow-sm">
        <div class="px-8 py-6 border-b border-gray-50 bg-slate-50/50 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Order List</h2>
            <span
                class="px-3 py-1 bg-white border border-gray-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest shadow-sm">
                {{ $orders->total() }} total
            </span>
        </div>

        @if ($orders->count() === 0)
            <div class="p-16 text-center">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8.25 6.75h12M8.25 12h12M8.25 17.25h12M3.75 6.75h.007v.008H3.75V6.75Zm0 5.25h.007v.008H3.75V12Zm0 5.25h.007v.008H3.75v-.008Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">No orders found</h3>
                <p class="text-slate-400 text-sm mt-1">Try changing filters or search keyword.</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach ($orders as $o)
                    @php
                        $when =
                            $o->schedule_type === 'scheduled' && $o->scheduled_at ? $o->scheduled_at : $o->created_at;
                    @endphp

                    <div class="group px-8 py-6 hover:bg-slate-50/50 transition-colors">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-6">

                            {{-- Left: ID + Status --}}
                            <div class="min-w-[180px]">
                                <div class="text-xs font-black text-slate-400 uppercase tracking-widest">Order</div>
                                <div class="mt-1 flex items-center gap-2">
                                    <div class="text-lg font-extrabold text-slate-900">#{{ $o->id }}</div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-bold border {{ $statusConfig($o->status) }} uppercase tracking-wider">
                                        {{ str_replace('_', ' ', $o->status) }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm font-bold text-slate-700">
                                    {{ $serviceLabel($o->service_type) }}
                                    <span class="text-slate-300 mx-2">•</span>
                                    <span class="uppercase">{{ $o->payment_type ?? 'cash' }}</span>
                                </div>
                            </div>

                            {{-- Middle: Route --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col items-center gap-1 shrink-0">
                                        <div class="h-2 w-2 rounded-full bg-slate-300"></div>
                                        <div class="h-3 w-px bg-slate-200"></div>
                                        <div class="h-2 w-2 rounded-full bg-slate-900"></div>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-400 truncate">{{ $o->pickup }}</div>
                                        <div class="text-sm font-black text-slate-900 truncate">{{ $o->dropoff }}</div>
                                        <div class="text-xs text-slate-400 font-semibold mt-1">
                                            {{ $when->format('d M, h:i A') }}
                                            <span class="text-slate-300 mx-2">•</span>
                                            Shift: <span class="capitalize">{{ $o->shift ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right: Driver + Action --}}
                            <div class="shrink-0 flex flex-col lg:items-end gap-2">
                                <div class="text-xs text-slate-400 font-bold uppercase tracking-widest">Driver</div>
                                <div class="text-sm font-extrabold text-slate-900">
                                    {{ $o->driver?->name ?? 'Not Assigned' }}
                                </div>

                                <a href="{{ route('manager.orders.show', $o) }}"
                                    class="inline-flex items-center justify-center h-10 px-5 rounded-xl border border-gray-200 bg-white text-sm font-bold text-slate-600 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                                    View & Assign
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-8 py-6 bg-slate-50/30 border-t border-gray-50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
