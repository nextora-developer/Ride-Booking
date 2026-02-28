@extends('layouts.driver-app')

@section('title', 'Profile')

@section('header')
    <div class="relative px-4 pt-4 pb-2 sticky top-0 z-30 border-b border-slate-50">
        <div class="absolute left-0 top-1/2 -translate-y-1/2">
            <a href="{{ route('driver.dashboard') }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-200 text-slate-700 active:scale-90 transition-all shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>

        <div class="text-center">
            <h1 class="text-lg font-black text-slate-800 leading-none">个人资料</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">My Profile</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto px-1 space-y-6 pb-7">

        {{-- 1. 顶部身份卡 --}}
        <div
            class="rounded-[2.5rem] bg-white border border-slate-200
                   shadow-[0_14px_34px_rgba(15,23,42,0.08)]
                   p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-slate-900"></div>

            <div
                class="mx-auto h-20 w-20 rounded-[2rem]
                       bg-white border border-slate-200
                       flex items-center justify-center text-3xl
                       shadow-[0_18px_45px_rgba(15,23,42,0.10)]">
                👤
            </div>

            <h1 class="text-2xl font-black text-slate-900 mt-4">{{ $driver->name }}</h1>

            @php
                $status = strtolower((string) ($driver->driver_status ?? ''));
                $statusLabel = match ($status) {
                    'active', 'approved', 'online' => '正常',
                    'pending', 'review' => '待审核',
                    'banned', 'blocked', 'inactive' => '已停用',
                    default => ($driver->driver_status ?: '正常'),
                };

                $statusBadge = match ($status) {
                    'pending', 'review' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'banned', 'blocked', 'inactive' => 'bg-rose-50 text-rose-800 border-rose-200',
                    default => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                };
            @endphp

            <div
                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mt-3 border {{ $statusBadge }}">
                {{ $statusLabel }}
            </div>

            <p class="text-xs font-bold text-slate-500 mt-4 uppercase tracking-tighter">
                如需修改资料，请联系管理层
            </p>
        </div>

        {{-- 2. 详细资料分组 --}}
        <div class="space-y-8 px-2">

            {{-- 分组：基本信息 --}}
            <section>
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-2">基本资料</h3>

                <div
                    class="bg-white rounded-[2rem] border border-slate-200
                           shadow-[0_14px_34px_rgba(15,23,42,0.06)]
                           divide-y divide-slate-100 overflow-hidden">
                    @foreach ([
                        ['label' => '姓名', 'value' => $driver->full_name],
                        ['label' => '身份证号码', 'value' => $driver->ic_number],
                        ['label' => '电话号码', 'value' => $driver->phone],
                        ['label' => '邮箱', 'value' => $driver->email],
                    ] as $it)
                        <div class="p-5 flex justify-between items-start gap-4">
                            <span
                                class="text-[11px] font-black text-slate-500 uppercase tracking-wider shrink-0 mt-0.5">{{ $it['label'] }}</span>
                            <span class="text-sm font-black text-slate-900 text-right break-all">
                                {{ $it['value'] ?: '-' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- 分组：车辆与班次 --}}
            <section>
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-2">车辆与班次</h3>

                <div class="grid grid-cols-2 gap-3">
                    @foreach ([
                        ['label' => '车牌号码', 'value' => $driver->car_plate, 'icon' => '🆔'],
                        ['label' => '车型', 'value' => $driver->car_model, 'icon' => '🚗'],
                        ['label' => '班次', 'value' => $driver->shift ? strtoupper($driver->shift) : null, 'icon' => '⏰'],
                        ['label' => '在线状态', 'value' => ($driver->is_online ?? 0) ? '在线' : '离线', 'icon' => '📶'],
                    ] as $it)
                        <div
                            class="bg-white p-5 rounded-[1.75rem] border border-slate-200
                                   shadow-[0_14px_34px_rgba(15,23,42,0.06)]">
                            <div class="text-[20px] mb-2">{{ $it['icon'] }}</div>
                            <div class="text-[9px] font-black text-slate-500 uppercase tracking-tighter">{{ $it['label'] }}
                            </div>
                            <div class="text-sm font-black text-slate-900 mt-1">{{ $it['value'] ?: '-' }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- 分组：银行资料 --}}
            <section>
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-2">银行资料</h3>

                <div
                    class="bg-slate-900 rounded-[2rem] p-6 text-white
                           shadow-[0_22px_55px_rgba(15,23,42,0.25)]
                           relative overflow-hidden border border-white/10">
                    <div class="absolute right-[-12%] top-[-22%] w-44 h-44 bg-white/5 rounded-full blur-xl"></div>
                    <div class="absolute left-[-10%] bottom-[-20%] w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl"></div>

                    <div class="relative">
                        <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest">银行名称</div>
                        <div class="text-lg font-black mt-1 text-white">{{ $driver->bank_name ?: '-' }}</div>

                        <div class="mt-6 text-[10px] font-black text-slate-300 uppercase tracking-widest">银行账号</div>
                        <div class="text-xl font-black mt-1 tracking-wider text-indigo-100">
                            {{ $driver->bank_account ?: '-' }}
                        </div>

                        <div class="mt-6 pt-5 border-t border-white/10">
                            <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest">结算提醒</div>
                            <div class="text-xs font-bold text-slate-200 mt-1 leading-relaxed">
                                若银行资料有误，请尽快联系管理层协助更新，以免影响结算。
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection