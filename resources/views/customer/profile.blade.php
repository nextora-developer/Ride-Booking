@extends('layouts.customer-app')

@section('title', '个人资料')

@section('header')
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 px-2">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">个人资料</h1>
            <p class="text-slate-500 font-medium mt-1">更新你的基本资料与联系方式</p>
        </div>

        {{-- <div class="flex items-center gap-2">
            <a href="{{ route('customer.orders.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-3.3-6.9" />
                </svg>
                我的订单
            </a>
        </div> --}}
    </div>
@endsection

@section('content')
    @php
        $u = auth()->user();
        $initial = strtoupper(substr($u->name ?? 'U', 0, 1));
    @endphp

    <div class="pb-24 space-y-6">

        {{-- Profile Summary --}}
        <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-50">
            <div class="flex items-center gap-5">
                <div class="h-16 w-16 rounded-[2rem] bg-slate-900 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-slate-200">
                    {{ $initial }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xl font-black text-slate-900 truncate">{{ $u->name }}</div>
                    <div class="text-sm font-bold text-slate-400 truncate">{{ $u->email }}</div>

                    <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-black uppercase tracking-widest">
                        Customer
                    </div>
                </div>
            </div>
        </div>

        {{-- Editable Fields --}}
        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Display Name --}}
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-50">
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">
                        基本资料
                    </div>

                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1 mb-2">
                        Username
                    </label>
                    <div class="group relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0" />
                            </svg>
                        </div>
                        <input name="name" value="{{ old('name', $u->name) }}" required
                            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                    </div>
                    @error('name') <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror

                    <div class="mt-6">
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1 mb-2">
                            Full Name (Optional)
                        </label>
                        <div class="group relative">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0" />
                                </svg>
                            </div>
                            <input name="full_name" value="{{ old('full_name', $u->full_name) }}" placeholder="Full legal name"
                                class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                        </div>
                        @error('full_name') <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Contact --}}
                <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-50">
                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">
                        联系方式
                    </div>

                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1 mb-2">
                        Phone (Optional)
                    </label>
                    <div class="group relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                        </div>
                        <input name="phone" value="{{ old('phone', $u->phone) }}" placeholder="012-3456789"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                    </div>
                    @error('phone') <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror

                    <div class="mt-6">
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1 mb-2">
                            IC Number (Optional)
                        </label>
                        <div class="group relative">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7.5H9m6 3H9m10.5 9H4.5A2.25 2.25 0 012.25 17.25V6.75A2.25 2.25 0 014.5 4.5h15A2.25 2.25 0 0121.75 6.75v10.5A2.25 2.25 0 0119.5 19.5Z" />
                                </svg>
                            </div>
                            <input name="ic_number" value="{{ old('ic_number', $u->ic_number) }}" placeholder="IC / NRIC"
                                class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                        </div>
                        @error('ic_number') <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-6">
                        <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            Email
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-100 px-4 py-4 text-sm font-black text-slate-700">
                            {{ $u->email }}
                            <div class="text-[11px] font-bold text-slate-400 mt-1">Email 需从系统管理员协助更换</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Security --}}
            <div class="bg-slate-900 rounded-[2.5rem] p-6 text-white shadow-xl shadow-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-black text-slate-300 uppercase tracking-widest">Security</div>
                        <div class="text-lg font-black mt-1">账号安全</div>
                        <div class="text-sm font-bold text-slate-300 mt-1">建议定期更换密码，保护账号安全</div>
                    </div>

                    <a href="{{ route('customer.password.edit') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-sm font-black transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5Z" />
                        </svg>
                        更换密码
                    </a>
                </div>
            </div>

            {{-- Save --}}
            <div class="flex flex-col md:flex-row gap-3 justify-end">
                <a href="{{ route('customer.home') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-black hover:bg-slate-50 transition">
                    取消
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white font-black shadow-lg shadow-slate-200 hover:bg-slate-800 active:scale-[0.98] transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" />
                    </svg>
                    保存更改
                </button>
            </div>

        </form>
    </div>
@endsection