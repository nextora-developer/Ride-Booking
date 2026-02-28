<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 py-12 bg-slate-50/50">
        <div class="w-full max-w-md">

            <div
                class="bg-white rounded-[3rem] shadow-[0_24px_70px_rgba(15,23,42,0.08)] border border-slate-200/60 overflow-hidden relative">
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50">
                </div>

                <div class="p-10">
                    {{-- Header --}}
                    <div class="text-center mb-10">
                        <div class="inline-flex relative mb-6">
                            <div class="absolute inset-0 bg-indigo-500 blur-2xl opacity-20 rounded-full"></div>
                            <div
                                class="relative h-20 w-20 rounded-[2rem] bg-slate-900 flex items-center justify-center shadow-xl shadow-slate-200">
                                <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </div>
                        </div>

                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">司机注册</h1>
                        <p class="text-[13px] font-bold text-slate-400 mt-1 uppercase tracking-wider">
                            创建你的司机账号
                        </p>
                    </div>

                    {{-- Global errors --}}
                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
                            <div class="text-xs font-black text-rose-700 uppercase tracking-widest mb-1">
                                请修正以下错误
                            </div>
                            <ul class="text-sm font-semibold text-rose-700 list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('driver.register.store') }}" class="space-y-5">
                        @csrf

                        {{-- Name (login/display name) --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                用户名
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                                    </svg>
                                </div>
                                <input name="name" type="text" required value="{{ old('name') }}"
                                    placeholder="司机显示名称"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('name')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Full Name (IC name) --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                姓名（与身份证一致）
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                                    </svg>
                                </div>
                                <input name="full_name" type="text" required value="{{ old('full_name') }}"
                                    placeholder="身份证姓名"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('full_name')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- IC Number --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                身份证号码（IC/NRIC）
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 7.5H9m6 3H9m10.5 9H4.5A2.25 2.25 0 0 1 2.25 17.25V6.75A2.25 2.25 0 0 1 4.5 4.5h15A2.25 2.25 0 0 1 21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5Z" />
                                    </svg>
                                </div>
                                <input name="ic_number" type="text" required value="{{ old('ic_number') }}"
                                    placeholder="IC / NRIC 号码"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('ic_number')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                手机号码
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25z" />
                                    </svg>
                                </div>
                                <input name="phone" type="text" required value="{{ old('phone') }}"
                                    placeholder="012-3456789"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('phone')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                电子邮箱
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 2.25 17.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75m19.5 0l-9.75 6.75L2.25 6.75" />
                                    </svg>
                                </div>
                                <input name="email" type="email" required value="{{ old('email') }}"
                                    placeholder="driver@email.com"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('email')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Car Plate --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                车牌号码
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 18.75h7.5m-9-3.75h10.5M6.75 15l.9-6.3A2.25 2.25 0 0 1 9.88 6.75h4.24A2.25 2.25 0 0 1 16.35 8.7l.9 6.3M7.5 18.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm9 0a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                                    </svg>
                                </div>
                                <input name="car_plate" type="text" required value="{{ old('car_plate') }}"
                                    placeholder="ABC 1234"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('car_plate')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Car Model --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                车辆型号
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 18.75h7.5m-9-3.75h10.5M6.75 15l.9-6.3A2.25 2.25 0 0 1 9.88 6.75h4.24A2.25 2.25 0 0 1 16.35 8.7l.9 6.3M7.5 18.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm9 0a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                                    </svg>
                                </div>
                                <input name="car_model" type="text" required value="{{ old('car_model') }}"
                                    placeholder="Toyota Vios"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('car_model')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bank Name --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                银行名称
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10.5h18M4.5 10.5V19.5m3-9v9m3-9v9m3-9v9m3-9V19.5M2.25 19.5h19.5M12 3l9.75 6H2.25L12 3Z" />
                                    </svg>
                                </div>
                                <input name="bank_name" type="text" required value="{{ old('bank_name') }}"
                                    placeholder="Maybank / CIMB"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('bank_name')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bank Account --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                银行账号
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10.5h18M4.5 10.5V19.5m3-9v9m3-9v9m3-9v9m3-9V19.5M2.25 19.5h19.5M12 3l9.75 6H2.25L12 3Z" />
                                    </svg>
                                </div>
                                <input name="bank_account" type="text" required value="{{ old('bank_account') }}"
                                    placeholder="银行账号"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('bank_account')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Shift --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                选择班次
                            </label>

                            <select name="shift" required
                                class="w-full px-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent
                                       focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50
                                       transition-all outline-none text-sm font-bold text-slate-900">
                                <option value="">请选择班次</option>
                                <option value="day" {{ old('shift') === 'day' ? 'selected' : '' }}>早班</option>
                                <option value="night" {{ old('shift') === 'night' ? 'selected' : '' }}>晚班</option>
                            </select>
                            @error('shift')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                密码
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z" />
                                    </svg>
                                </div>
                                <input name="password" type="password" required placeholder="••••••••"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                            @error('password')
                                <p class="mt-1 ml-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">
                                确认密码
                            </label>
                            <div class="group relative">
                                <div
                                    class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z" />
                                    </svg>
                                </div>
                                <input name="password_confirmation" type="password" required placeholder="••••••••"
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white focus:ring-4 focus:ring-indigo-50/50 transition-all outline-none text-sm font-bold text-slate-900 placeholder:text-slate-300" />
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="group w-full relative mt-4 bg-slate-900 text-white rounded-2xl p-1
                                   shadow-[0_10px_30px_rgba(15,23,42,0.2)] hover:shadow-[0_15px_35px_rgba(15,23,42,0.3)]
                                   active:scale-[0.98] transition-all duration-200">
                            <div class="relative py-4 px-6 flex items-center justify-center gap-3">
                                <span class="text-sm font-black uppercase tracking-widest">
                                    注册成为司机
                                </span>
                                <svg class="h-5 w-5 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12l-7.5 7.5M3 12h18" />
                                </svg>
                            </div>
                        </button>
                    </form>

                    <div class="mt-8 text-center space-y-3">
                        <p class="text-sm font-bold text-slate-500">
                            已经注册？
                            <a href="{{ route('driver.login') }}"
                                class="text-indigo-600 hover:text-indigo-800 transition">
                                登录
                            </a>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-guest-layout>