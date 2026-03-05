@extends('layouts.customer-app')

@section('title', '挂账记录')

@section('header')
    <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight truncate">挂账记录</h1>
            <p class="text-sm text-slate-500 font-semibold truncate">
                仅供查看，不能自行修改
            </p>
        </div>

        <button onclick="window.location.reload()"
            class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 shadow-sm hover:bg-slate-50 active:scale-95 transition">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
        </button>
    </div>
@endsection

@section('content')

    {{-- 列表 --}}
    <div class="space-y-4">

        @forelse ($logs as $log)
            @php
                $change = (float) ($log->change ?? 0);

                $isPositive = $change > 0; // 增加欠账
                $isNegative = $change < 0; // 减少欠账

                $badgeClass = $isPositive
                    ? 'bg-rose-100 text-rose-700 border-rose-200'
                    : ($isNegative
                        ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                        : 'bg-slate-100 text-slate-700 border-slate-200');

                $amountClass = $isPositive ? 'text-rose-600' : ($isNegative ? 'text-emerald-600' : 'text-slate-700');

                $sign = $isPositive ? '+' : ($isNegative ? '-' : '');
                $actionText = $isPositive ? '增加挂账' : ($isNegative ? '减少挂账' : (string) $log->action);
            @endphp

            <div class="bg-white rounded-[2.2rem] p-5 border border-slate-200 shadow-[0_14px_34px_rgba(15,23,42,0.06)]">

                {{-- 顶部：操作类型 + 金额 --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black uppercase tracking-widest border {{ $badgeClass }}">
                            {{ $actionText }}
                        </span>

                        <div class="mt-2 text-xs font-bold text-slate-500">
                            {{ $log->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <div class="text-lg font-black {{ $amountClass }}">
                            {{ $sign }}RM {{ number_format(abs($change), 2) }}
                        </div>
                    </div>
                </div>

                {{-- 备注 --}}
                @if (!empty($log->note))
                    <div class="mt-4 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">备注</div>
                        <div class="mt-1 text-sm font-bold text-slate-700 leading-relaxed">

                            @php
                                $raw = trim((string) $log->note);
                                $noteText = $raw;

                                // ✅ 1) 订单完成（挂账）: 不管是 Order #xx completed (credit) 还是 订单 #xx completed 已完成（挂账）
                                if (
                                    preg_match(
                                        '/(?:Order|订单)\s*#(\d+).*completed.*(credit|\(credit\)|挂账)/i',
                                        $raw,
                                        $m,
                                    )
                                ) {
                                    $noteText = "订单 #{$m[1]} 已完成（挂账）";
                                }
                                // ✅ 2) 经理 / 管理员 操作（允许大小写/多空格）
                                elseif (preg_match('/^Manager\s+credit\s+adjustment\s*$/i', $raw)) {
                                    $noteText = '经理调整欠账';
                                } elseif (preg_match('/^Manager\s+cleared\s+credit\s*$/i', $raw)) {
                                    $noteText = '经理清除欠账';
                                } elseif (preg_match('/^Admin\s+credit\s+adjustment\s*$/i', $raw)) {
                                    $noteText = '管理员调整欠账';
                                } elseif (preg_match('/^Admin\s+cleared\s+credit\s*$/i', $raw)) {
                                    $noteText = '管理员清除欠账';
                                }

                                // ✅ 3) 兜底：如果还有 completed 字样，强制去掉
                                $noteText = preg_replace('/\bcompleted\b/i', '', $noteText);
                                $noteText = trim(preg_replace('/\s+/', ' ', $noteText));
                            @endphp

                            {{ $noteText }}

                        </div>
                    </div>
                @endif

                {{-- 底部信息 --}}
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">操作人</div>
                        <div class="text-sm font-extrabold text-slate-800 truncate">
                            {{ $log->actor_name ?? '管理员 / 调度' }}
                        </div>
                    </div>

                    {{-- 显示余额 --}}
                    @if (isset($log->after))
                        <div class="text-right shrink-0">
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">当前欠账</div>
                            <div class="text-sm font-black text-slate-800">
                                RM {{ number_format((float) $log->after, 2) }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="text-center py-16 bg-white/60 border border-dashed border-slate-200 rounded-[2.2rem]">
                <div class="text-sm font-mono text-slate-400">暂无记录</div>
                <div class="mt-2 text-xs font-bold text-slate-500">目前没有挂账记录</div>
            </div>
        @endforelse

        {{-- 分页 --}}
        @if ($logs->hasPages())
            <div class="pt-2">
                {{ $logs->links() }}
            </div>
        @endif

    </div>

@endsection
