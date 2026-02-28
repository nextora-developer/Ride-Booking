<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 py-12 bg-slate-100">
        <div class="w-full max-w-md">

            <div
                class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.08)] border border-slate-200 overflow-hidden relative">

                {{-- 顶部装饰线 --}}
                <div class="h-1 bg-gradient-to-r from-transparent via-slate-900 to-transparent opacity-40"></div>

                <div class="p-10">

                    {{-- 标题 --}}
                    <div class="text-center mb-10">
                        <div class="inline-flex relative mb-6">
                            <div class="absolute inset-0 bg-slate-900 blur-2xl opacity-10 rounded-full"></div>
                            <div
                                class="relative h-20 w-20 rounded-[2rem] bg-slate-900 flex items-center justify-center shadow-xl shadow-slate-200">

                                <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                                </svg>

                            </div>
                        </div>

                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                            经理登录入口
                        </h1>
                        <p class="text-[12px] font-bold text-slate-400 mt-2 uppercase tracking-widest">
                            派单管理控制台
                        </p>
                    </div>

                    {{-- 错误提示 --}}
                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
                            <div class="text-xs font-black text-rose-700 uppercase tracking-widest mb-1">
                                登录失败
                            </div>
                            <ul class="text-sm font-semibold text-rose-700 list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('manager.login.store') }}" class="space-y-6">
                        @csrf

                        {{-- 邮箱 --}}
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                经理邮箱
                            </label>
                            <input type="email" name="email" required
                                class="mt-2 w-full rounded-2xl bg-slate-50 border-2 border-transparent px-4 py-4 text-sm font-bold text-slate-900
                                       focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5
                                       transition-all outline-none placeholder:text-slate-300"
                                placeholder="manager@email.com">
                        </div>

                        {{-- 密码 --}}
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                密码
                            </label>
                            <input type="password" name="password" required
                                class="mt-2 w-full rounded-2xl bg-slate-50 border-2 border-transparent px-4 py-4 text-sm font-bold text-slate-900
                                       focus:border-slate-900 focus:bg-white focus:ring-4 focus:ring-slate-900/5
                                       transition-all outline-none placeholder:text-slate-300"
                                placeholder="••••••••">
                        </div>

                        {{-- 登录按钮 --}}
                        <button type="submit"
                            class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest
                                   shadow-lg shadow-slate-200 hover:bg-slate-800 active:scale-[0.98] transition-all">
                            登录经理账户
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
