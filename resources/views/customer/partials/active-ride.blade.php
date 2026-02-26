@if (!$activeBooking)
    {{-- 没有进行中订单就回空（或你要显示提示也可以） --}}
    <div></div>
@else
    @php
        $statusMap = [
            'pending' => '等待派单',
            'assigned' => '司机已接单',
            'on_the_way' => '司机正在前往',
            'arrived' => '司机已到达',
            'in_trip' => '行程进行中',
            'completed' => '已完成',
            'cancelled' => '已取消',
        ];

        $statusLabel = $statusMap[$activeBooking->status] ?? '进行中';
    @endphp

    <div class="mb-8">
        <div
            class="relative bg-gradient-to-r from-slate-900 to-slate-800 text-white
                   rounded-[2.5rem] p-6 overflow-hidden
                   shadow-[0_20px_50px_rgba(0,0,0,0.25)]">

            <div class="absolute -top-10 -right-10 h-40 w-40 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col gap-5">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-white/60 font-bold">当前行程</p>
                        <h2 class="text-lg font-black">{{ $statusLabel }}</h2>
                    </div>

                    <span class="px-4 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur">
                        {{ strtoupper(str_replace('_', ' ', $activeBooking->status)) }}
                    </span>
                </div>

                <div class="text-sm font-semibold text-white/80">
                    {{ $activeBooking->pickup }} → {{ $activeBooking->dropoff }}
                </div>

                @if ($activeBooking->driver)
                    <div class="flex items-center justify-between bg-white/5 rounded-2xl p-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center font-black text-lg">
                                {{ strtoupper(substr($activeBooking->driver->name, 0, 1)) }}
                            </div>

                            <div>
                                <div class="font-bold text-white">{{ $activeBooking->driver->name }}</div>
                                <div class="text-xs text-white/60 font-semibold">专属司机</div>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xs text-white/50">联系电话</div>
                            <div class="font-bold text-white">
                                {{ $activeBooking->driver->phone ?? '—' }}
                            </div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('customer.orders.show', $activeBooking) }}"
                    class="w-full inline-flex items-center justify-center gap-2
                           bg-white text-slate-900 font-bold py-3 rounded-2xl
                           hover:bg-slate-100 transition-all">
                    查看详情
                </a>

            </div>
        </div>
    </div>
@endif
