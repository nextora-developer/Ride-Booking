@if (!$activeBooking)
    <div data-has-active-ride="0"></div>
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

    <div class="mb-8" data-has-active-ride="1">
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

                @php
                    $routePoints = [$activeBooking->pickup];

                    if (!empty($activeBooking->dropoffs)) {
                        $routePoints = array_merge($routePoints, $activeBooking->dropoffs);
                    } elseif (!empty($activeBooking->dropoff)) {
                        $routePoints[] = $activeBooking->dropoff;
                    }

                    $routeText = implode(' → ', $routePoints);
                @endphp

                <div class="text-sm font-semibold text-white/80 truncate">
                    {{ $routeText }}
                </div>

                @if ($activeBooking->driver)
                    @php
                        $phoneRaw = (string) ($activeBooking->driver->phone ?? '');
                        $phone = trim($phoneRaw);
                        $tel = preg_replace('/[^0-9+]/', '', $phone);
                    @endphp

                    <div class="bg-white/5 rounded-2xl p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <div
                                    class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center font-black text-lg text-white shrink-0">
                                    {{ strtoupper(substr($activeBooking->driver->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <div class="font-bold text-white truncate">
                                        {{ $activeBooking->driver->full_name }}
                                    </div>

                                    <div class="mt-1 text-sm font-bold text-white/60 truncate tabular-nums">
                                        {{ $phone ?: '—' }}
                                    </div>
                                </div>
                            </div>

                            @if ($phone)
                                <a href="tel:{{ $tel }}" aria-label="Call driver"
                                    class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-emerald-500 text-white
                                           shadow-lg shadow-emerald-500/30 hover:bg-emerald-400 active:scale-95 transition">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.11 5.18
                                                2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.72c.12.86.3 1.7.54 2.5
                                                a2 2 0 0 1-.45 2.11L9.1 10.9a16 16 0 0 0 4 4l1.57-1.09
                                                a2 2 0 0 1 2.11-.45c.8.24 1.64.42 2.5.54A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                </a>
                            @else
                                <div
                                    class="h-12 w-12 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-white/40">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.11 5.18 2 2 0 0 1 5.1 3h3" />
                                        <path d="M10 7a14 14 0 0 0 7 7" />
                                        <path d="M2 2l20 20" />
                                    </svg>
                                </div>
                            @endif
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

    {{-- <script>
        (function() {
            const wrap = document.getElementById('activeRideWrap');
            const card = document.getElementById('activeRideCard');

            if (!wrap || !card) return;

            // 一旦 active ride 出现，先停掉 dashboard 那边的 timer
            if (window.stopDashboardActiveRidePolling) {
                window.stopDashboardActiveRidePolling();
            }

            // 避免重复开多个 timer
            if (window.activeRideSelfTimer) {
                clearInterval(window.activeRideSelfTimer);
                window.activeRideSelfTimer = null;
            }

            async function refreshActiveRideSelf() {
                if (document.hidden) return;

                try {
                    const res = await fetch("{{ route('customer.active.ride') }}", {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!res.ok) return;

                    const html = await res.text();
                    wrap.innerHTML = html;

                    // 如果更新后已经没有 active ride 了，停掉自己
                    const stillActive = wrap.querySelector('[data-has-active-ride="1"]');

                    if (!stillActive && window.activeRideSelfTimer) {
                        clearInterval(window.activeRideSelfTimer);
                        window.activeRideSelfTimer = null;

                        // 没有 active ride 后，重新交回 dashboard 那边处理
                        window.location.reload();
                    }
                } catch (e) {
                    // 静默失败
                }
            }

            window.activeRideSelfTimer = setInterval(refreshActiveRideSelf, 10000);

            window.addEventListener('beforeunload', function() {
                if (window.activeRideSelfTimer) {
                    clearInterval(window.activeRideSelfTimer);
                    window.activeRideSelfTimer = null;
                }
            });
        })();
    </script> --}}
@endif
