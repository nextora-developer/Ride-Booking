@extends('layouts.admin-app')

@section('title', '经理详情 - ' . $manager->name)

@section('header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">经理资料详情</h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.managers.index') }}"
                class="inline-flex items-center justify-center h-10 px-4 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all shadow-sm">
                返回列表
            </a>
            <a href="{{ route('admin.managers.edit', $manager) }}"
                class="inline-flex items-center justify-center h-10 px-4 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                编辑资料
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- Left Column: Information Card --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
                {{-- Profile Header Section --}}
                <div class="relative h-32 bg-slate-900">
                    <div class="absolute -bottom-12 left-8 p-1 bg-white rounded-[2rem]">
                        <div class="h-24 w-24 rounded-[1.75rem] bg-gradient-to-br from-slate-700 to-black text-white flex items-center justify-center text-3xl font-black shadow-inner">
                            {{ strtoupper(mb_substr($manager->name ?? 'M', 0, 1)) }}
                        </div>
                    </div>
                </div>

                <div class="pt-16 px-8 pb-8">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-100 pb-8">
                        <div>
                            <h2 class="text-3xl font-black text-slate-900">{{ $manager->name }}</h2>
                            <p class="text-sm font-bold text-slate-400 mt-1 uppercase tracking-widest">系统 ID: #{{ $manager->id }}</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-black uppercase tracking-tighter">
                                {{ $manager->shift === 'day' ? '☀️ 白班模式' : '🌙 夜班模式' }}
                            </span>
                        </div>
                    </div>

                    {{-- Data Grid --}}
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">真实全名</label>
                            <p class="text-base font-bold text-slate-800">{{ $manager->full_name ?? '未填写' }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">电子邮箱</label>
                            <div class="flex items-center gap-2">
                                <span class="text-base font-bold text-slate-800">{{ $manager->email }}</span>
                                <button class="p-1 text-slate-400 hover:text-blue-500 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></button>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">联系电话</label>
                            <p class="text-base font-bold text-slate-800">{{ $manager->phone ?? '未绑定' }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">入职时间</label>
                            <p class="text-base font-bold text-slate-800">{{ optional($manager->created_at)->format('Y-m-d') }} <span class="text-xs font-medium text-slate-400 ml-1">{{ optional($manager->created_at)->format('H:i') }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-slate-300 animate-pulse"></div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">最后活动记录：{{ optional($manager->updated_at)->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        {{-- Right Column: Status & Actions --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white border border-slate-200 rounded-[2rem] p-8 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6 text-center">当前账号状态</h3>
                
                <div class="flex flex-col items-center py-4">
                    @if($manager->is_active)
                        <div class="relative">
                            <div class="absolute inset-0 bg-emerald-400 blur-xl opacity-20 rounded-full"></div>
                            <div class="relative w-20 h-20 rounded-full bg-emerald-50 border-4 border-white shadow-sm flex items-center justify-center">
                                <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <p class="mt-4 text-lg font-black text-slate-900">正常启用中</p>
                    @else
                        <div class="w-20 h-20 rounded-full bg-rose-50 border-4 border-white shadow-sm flex items-center justify-center">
                            <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        </div>
                        <p class="mt-4 text-lg font-black text-slate-900">账号已禁用</p>
                    @endif
                </div>

                <div class="mt-8">
                    <form action="{{ route('admin.managers.toggle', $manager) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full h-12 rounded-xl text-sm font-bold transition-all active:scale-95 shadow-sm
                                   {{ $manager->is_active
                                       ? 'bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-100'
                                       : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border border-emerald-100' }}">
                            {{ $manager->is_active ? '禁用此账号' : '立即启用账号' }}
                        </button>
                    </form>
                    <p class="mt-3 text-[10px] text-center text-slate-400 leading-relaxed px-4">
                        禁用账号后，该经理将无法登录管理后台，但其历史数据会被保留。
                    </p>
                </div>
            </div>

            {{-- Quick Links Card --}}
            {{-- <div class="bg-slate-900 rounded-[2rem] p-8 text-white shadow-xl shadow-slate-200">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-500 mb-6">快捷管理操作</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.managers.edit', $manager) }}"
                        class="group flex items-center justify-between w-full h-12 px-5 rounded-xl bg-slate-800 hover:bg-white hover:text-slate-900 transition-all">
                        <span class="text-sm font-bold">修改资料</span>
                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    
                    <button class="group flex items-center justify-between w-full h-12 px-5 rounded-xl bg-slate-800 hover:bg-white hover:text-slate-900 transition-all">
                        <span class="text-sm font-bold">操作日志</span>
                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div> --}}
        </div>
    </div>
@endsection