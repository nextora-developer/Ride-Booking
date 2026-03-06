@extends('layouts.customer-app')

@section('title', '订单评价')

@section('header')
    <div class="relative px-2">
        {{-- Mobile Navigation --}}
        <div class="md:hidden flex items-center justify-between h-7">
            <a href="{{ route('customer.orders.show', $order) }}"
                class="inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-white border border-slate-100 text-slate-900 shadow-sm active:scale-90 transition-transform">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>

            <div class="text-center">
                <h1 class="text-base font-black text-slate-900">订单评价</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    编号：#{{ $order->id }}
                </p>
            </div>

            <div class="w-11"></div>
        </div>

        {{-- Desktop Header --}}
        <div class="hidden md:flex items-center justify-between pb-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">订单评价</h1>
                <p class="text-slate-500 font-medium">订单编号 #{{ $order->id }}</p>
            </div>

            <a href="{{ route('customer.orders.show', $order) }}"
                class="px-6 py-2.5 rounded-2xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                返回订单
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="pb-10 space-y-6">

        {{-- Order Summary --}}
        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-200 shadow-[0_14px_34px_rgba(15,23,42,0.08)]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">本次行程</p>
                    <h2 class="text-xl font-black text-slate-900">请为本次服务打分</h2>
                    <p class="text-sm font-bold text-slate-500 mt-2 leading-relaxed">
                        您的真实反馈可以帮助我们持续提升服务体验。
                    </p>
                </div>

                <div
                    class="h-14 w-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center shadow-sm shrink-0">
                    <span class="text-2xl">⭐</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                    <div class="text-[11px] font-black text-slate-500 uppercase tracking-widest">上车地点</div>
                    <div class="mt-2 text-sm font-bold text-slate-900 leading-relaxed">
                        {{ $order->pickup }}
                    </div>
                </div> --}}

                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                    <div class="text-[11px] font-black text-slate-500 uppercase tracking-widest">司机</div>
                    <div class="mt-2 text-sm font-bold text-slate-900 leading-relaxed">
                        {{ $order->driver->full_name ?? '未指派' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Review Form --}}
        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-200 shadow-[0_14px_34px_rgba(15,23,42,0.08)]">
            <form method="POST" action="{{ route('customer.reviews.store', $order) }}" class="space-y-6">
                @csrf

                {{-- Rating --}}
                <div x-data="{ rating: {{ old('rating', 0) }} }">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3">
                        服务评分
                    </label>

                    <div class="flex items-center gap-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}"
                                class="transition-transform duration-150 hover:scale-110 active:scale-95">
                                <svg class="h-10 w-10"
                                    :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-slate-300'"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.538 1.118l-3.377-2.455a1 1 0 00-1.176 0l-3.377 2.455c-.783.57-1.838-.197-1.538-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.098 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z" />
                                </svg>
                            </button>
                        @endfor
                    </div>

                    <input type="hidden" name="rating" :value="rating">

                    <div class="mt-3 text-xs font-bold text-slate-400">
                        <span x-show="rating === 0">请选择评分</span>
                        <span x-show="rating === 1">1 = 很差</span>
                        <span x-show="rating === 2">2 = 一般</span>
                        <span x-show="rating === 3">3 = 还可以</span>
                        <span x-show="rating === 4">4 = 满意</span>
                        <span x-show="rating === 5">5 = 非常满意</span>
                    </div>

                    @error('rating')
                        <div class="mt-2 text-sm font-bold text-rose-600">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Comment --}}
                <div>
                    <label for="comment"
                        class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3">
                        文字评价（可选）
                    </label>

                    <textarea id="comment" name="comment" rows="5"
                        class="w-full rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:bg-white focus:ring-0 transition resize-none"
                        placeholder="例如：司机很准时、服务很好、车子很干净...">{{ old('comment') }}</textarea>

                    @error('comment')
                        <div class="mt-2 text-sm font-bold text-rose-600">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 text-white text-sm font-black hover:bg-slate-800 active:scale-[0.98] transition shadow-lg shadow-slate-200">
                        <span>提交评价</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </button>

                    <a href="{{ route('customer.orders.show', $order) }}"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                        取消
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
