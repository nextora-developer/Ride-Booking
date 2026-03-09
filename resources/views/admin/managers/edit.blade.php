@extends('layouts.admin-app')

@section('title', '编辑经理 - ' . $manager->name)

@section('header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">更新经理资料</h1>
            <p class="mt-1 text-sm text-slate-500 font-medium">您正在修改 <span
                    class="text-slate-900 font-bold underline decoration-blue-500/30 underline-offset-4">{{ $manager->name }}</span>
                的账号权限与信息。</p>
        </div>

        <a href="{{ route('admin.managers.index') }}"
            class="inline-flex items-center justify-center h-10 px-4 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all shadow-sm">
            返回列表
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl">
        <form action="{{ route('admin.managers.update', $manager) }}" method="POST" class="group">
            @csrf
            @method('PATCH')

            <div class="bg-white border border-slate-200 rounded-[2rem] shadow-xl shadow-slate-200/40 overflow-hidden">

                {{-- Form Body --}}
                <div class="p-8 lg:p-12 space-y-10">

                    {{-- Section 1: Identity --}}
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">身份识别</h3>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">用于系统内部显示的名称及真实姓名。</p>
                        </div>
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">显示名称</label>
                                <input type="text" name="name" value="{{ old('name', $manager->name) }}"
                                    class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                @error('name')
                                    <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">全名</label>
                                <input type="text" name="full_name" value="{{ old('full_name', $manager->full_name) }}"
                                    class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                @error('full_name')
                                    <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-slate-100 to-transparent"></div>

                    {{-- Section 2: Contact --}}
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">联系信息</h3>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">确保经理能够收到系统通知和工作调度。</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">邮箱地址</label>
                                    <input type="email" name="email" value="{{ old('email', $manager->email) }}"
                                        class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                    @error('email')
                                        <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">联系电话</label>
                                    <input type="text" name="phone" value="{{ old('phone', $manager->phone) }}"
                                        class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                    @error('phone')
                                        <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">班次设置</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach (['day' => '白班', 'night' => '夜班'] as $value => $label)
                                        <label
                                            class="relative flex items-center justify-center h-11 rounded-xl border border-slate-200 cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-700 hover:bg-slate-50">
                                            <input type="radio" name="shift" value="{{ $value }}"
                                                class="sr-only" @checked(old('shift', $manager->shift) === $value)>
                                            <span class="text-sm font-bold">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-slate-100 to-transparent"></div>

                    {{-- Section 3: Account Status --}}
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">账号权限</h3>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">控制该账号是否可以登录后台管理系统。</p>
                        </div>
                        <div class="lg:col-span-2">
                            <label
                                class="group/toggle flex items-center justify-between p-4 rounded-2xl border-2 border-slate-100 bg-slate-50/50 hover:border-blue-100 hover:bg-blue-50/30 transition-all cursor-pointer">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover/toggle:scale-110 transition-transform shadow-sm">
                                        <div
                                            class="w-2.5 h-2.5 rounded-full {{ old('is_active', $manager->is_active) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900">允许经理登录</div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Toggle
                                            Account Availability</div>
                                    </div>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $manager->is_active) ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </section>
                </div>

                {{-- Footer --}}
                <div
                    class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[11px] font-bold uppercase tracking-wider">最后更新:
                            {{ $manager->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('admin.managers.show', $manager) }}"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center h-11 px-8 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-700 transition-all">
                            放弃更改
                        </a>
                        <button type="submit"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center h-11 px-8 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-black shadow-lg shadow-slate-200 active:scale-95 transition-all">
                            更新资料
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Optional: Additional Account Security Section --}}
        {{-- <div class="mt-8 p-6 rounded-[2rem] bg-rose-50 border border-rose-100 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white text-rose-500 flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-black text-rose-900 uppercase tracking-tight">安全与密码</h4>
                    <p class="text-xs text-rose-600/70 font-medium">若要重置经理密码，请前往专门的重置页面。</p>
                </div>
            </div>
            <button class="px-4 py-2 bg-white border border-rose-200 rounded-lg text-xs font-black text-rose-600 hover:bg-rose-600 hover:text-white transition-all uppercase tracking-widest shadow-sm">
                重置密码
            </button>
        </div> --}}
    </div>
@endsection
