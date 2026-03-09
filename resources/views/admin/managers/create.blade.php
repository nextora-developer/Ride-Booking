@extends('layouts.admin-app')

@section('title', '新增经理')

@section('header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">创建新账号</h1>
            <p class="mt-1 text-sm text-slate-500">为您的团队添加一名具有管理权限的新成员。</p>
        </div>

        <a href="{{ route('admin.managers.index') }}"
            class="inline-flex items-center justify-center h-10 px-4 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all shadow-sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            返回列表
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl">
        <form action="{{ route('admin.managers.store') }}" method="POST" class="group">
            @csrf

            {{-- Main Card --}}
            <div
                class="bg-white border border-slate-200 rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden transition-all group-focus-within:border-slate-300">

                {{-- Form Sections Container --}}
                <div class="p-8 lg:p-12 space-y-10">

                    {{-- Section 1: Identity --}}
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <h3 class="text-base font-bold text-slate-900">身份识别</h3>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">用于系统内部识别经理身份的信息。</p>
                        </div>
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">显示名称</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="例如：技术总监"
                                    class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                @error('name')
                                    <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">全名</label>
                                <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="请输入真实姓名"
                                    class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                @error('full_name')
                                    <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-slate-100 to-transparent"></div>

                    {{-- Section 2: Contact & Access --}}
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <h3 class="text-base font-bold text-slate-900">联系与排班</h3>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">经理的沟通渠道与日常工作时段。</p>
                        </div>
                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">邮箱地址</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        placeholder="name@company.com"
                                        class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                    @error('email')
                                        <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">联系电话</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="手机或座机号"
                                        class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                    @error('phone')
                                        <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">工作班次</label>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach (['day' => '白班', 'night' => '夜班'] as $value => $label)
                                        <label
                                            class="relative flex items-center justify-center h-11 rounded-xl border border-slate-200 cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-700 hover:bg-slate-50">
                                            <input type="radio" name="shift" value="{{ $value }}"
                                                class="sr-only" @checked(old('shift') === $value)>
                                            <span class="text-sm font-bold">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('shift')
                                    <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-slate-100 to-transparent"></div>

                    {{-- Section 3: Security --}}
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <h3 class="text-base font-bold text-slate-900">安全设置</h3>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">设置强密码以保护账号安全。</p>
                        </div>
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">登录密码</label>
                                <input type="password" name="password" placeholder="••••••••"
                                    class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                                @error('password')
                                    <p class="text-[11px] font-bold text-rose-500 ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">确认密码</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••"
                                    class="w-full h-11 rounded-xl border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-900 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>
                    </section>

                    {{-- Section 4: Status Toggle --}}
                    <div class="p-4 rounded-2xl bg-slate-900 text-white flex items-center justify-between shadow-lg">
                        <div class="flex items-center gap-4 ml-2">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold">激活经理账号</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-widest">允许该用户立即访问系统</div>
                            </div>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer mr-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', 1) ? 'checked' : '' }} class="sr-only peer">

                            <div
                                class="relative w-12 h-7 rounded-full bg-slate-700 transition-all duration-300
                   peer-checked:bg-emerald-500
                   after:content-['']
                   after:absolute
                   after:top-1
                   after:left-1
                   after:h-5
                   after:w-5
                   after:rounded-full
                   after:bg-white
                   after:shadow-md
                   after:transition-all
                   after:duration-300
                   peer-checked:after:translate-x-5">
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div
                    class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs font-medium text-slate-400">请确保所有必填项已正确填写后再提交。</p>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('admin.managers.index') }}"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center h-11 px-8 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-700 transition-all">
                            取消
                        </a>
                        <button type="submit"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center h-11 px-8 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 active:scale-95 transition-all">
                            确认创建
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
