@extends('layouts.admin-app')

@section('title', 'Edit Customer')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                Edit Customer
            </h1>
            <p class="mt-1 text-sm text-slate-500 font-semibold">
                Update customer information.
            </p>
        </div>

        <a href="{{ route('admin.customers.index') }}"
            class="h-10 px-4 inline-flex items-center justify-center rounded-2xl
                   bg-white border border-gray-200 text-sm font-extrabold
                   hover:bg-gray-50 transition">
            Back
        </a>
    </div>
@endsection


@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Main Form --}}
        <div class="lg:col-span-2">
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6 sm:p-8">

                <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                    @csrf
                    @method('PATCH')

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Basic Information</div>
                                <div class="text-xs text-slate-500 font-semibold mt-1">Customer profile & contact
                                    details.</div>
                            </div>
                            <span class="text-xs font-black text-slate-400">ID: {{ $customer->id }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Username / Display Name --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    Username / Display Name
                                </label>
                                <input type="text" name="name" value="{{ old('name', $customer->name) }}"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('name')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Full Name --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    Full Name
                                </label>
                                <input type="text" name="full_name" value="{{ old('full_name', $customer->full_name) }}"
                                    placeholder="e.g. Tan Ah Kow"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('full_name')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    Phone
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('phone')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    Email
                                </label>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('email')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- IC Number --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                                    IC Number
                                </label>
                                <input type="text" name="ic_number" value="{{ old('ic_number', $customer->ic_number) }}"
                                    class="w-full h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none transition">
                                @error('ic_number')
                                    <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Active Toggle --}}
                    <div class="my-5 rounded-2xl bg-gray-50 border border-gray-100 p-4 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-extrabold text-slate-900">Account Status</div>
                            <div class="text-xs text-slate-500 font-semibold mt-1">
                                Disable account to prevent login and booking.
                            </div>
                        </div>

                        <label class="inline-flex items-center gap-3">
                            <span
                                class="text-xs font-black {{ old('is_active', $customer->is_active) ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ old('is_active', $customer->is_active) ? 'ACTIVE' : 'INACTIVE' }}
                            </span>

                            <input type="checkbox" name="is_active" value="1"
                                class="h-5 w-5 rounded border-gray-300 text-black focus:ring-black"
                                @checked(old('is_active', $customer->is_active))>
                        </label>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="h-11 px-6 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                            Save Changes
                        </button>

                        <a href="{{ route('admin.customers.show', $customer) }}"
                            class="h-11 px-6 inline-flex items-center justify-center rounded-2xl bg-white border border-gray-200
                                       text-sm font-extrabold hover:bg-gray-50 transition">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

        {{-- RIGHT: Side Cards --}}
        <div class="space-y-6">

            {{-- Credit Summary Card --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6">

                <div class="text-xs font-black tracking-widest uppercase text-slate-400">
                    Credit Summary
                </div>

                @php
                    $balance = (float) ($customer->credit_balance ?? 0);
                @endphp

                <div class="mt-4 text-center">

                    {{-- Main Balance --}}
                    <div
                        class="text-3xl font-extrabold
            {{ $balance > 0 ? 'text-emerald-600' : ($balance < 0 ? 'text-rose-600' : 'text-slate-900') }}">
                        RM {{ number_format($balance, 2) }}
                    </div>

                    {{-- Status Text --}}
                    <div
                        class="mt-2 text-xs font-black uppercase tracking-widest
            {{ $balance > 0 ? 'text-emerald-600' : ($balance < 0 ? 'text-rose-600' : 'text-slate-400') }}">

                        @if ($balance > 0)
                            Customer Has Credit
                        @elseif ($balance < 0)
                            Customer Owes Money
                        @else
                            No Outstanding Balance
                        @endif

                    </div>

                </div>

                {{-- Extra Info --}}
                <div class="mt-6 grid grid-cols-2 gap-3 text-center text-xs font-semibold text-slate-500">
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 py-3">
                        Customer ID
                        <div class="mt-1 font-extrabold text-slate-900">
                            {{ $customer->id }}
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gray-50 border border-gray-100 py-3">
                        Status
                        <div
                            class="mt-1 font-extrabold
                {{ $customer->is_active ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $customer->is_active ? 'Active' : 'Inactive' }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- Adjust Credit Card --}}
            <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6">

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-extrabold text-slate-900">Adjust Credit</div>
                        <div class="text-xs text-slate-500 font-semibold mt-1">
                            Add / deduct credit, or clear to zero.
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Balance</div>
                        <div class="text-lg font-extrabold text-slate-900">
                            RM {{ number_format($customer->credit_balance ?? 0, 2) }}
                        </div>
                    </div>
                </div>

                <div class="mt-5 space-y-3">

                    {{-- Add Credit --}}
                    <form action="{{ route('admin.customers.credit.adjust', $customer) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="type" value="add">

                        <div class="flex gap-2">
                            <input type="number" step="0.01" min="0" name="amount" placeholder="Amount"
                                class="flex-1 h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                                           focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition">

                            <button type="submit"
                                class="h-11 px-4 rounded-2xl bg-emerald-600 text-white text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition">
                                Add
                            </button>
                        </div>
                    </form>

                    {{-- Deduct Credit --}}
                    <form action="{{ route('admin.customers.credit.adjust', $customer) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="type" value="deduct">

                        <div class="flex gap-2">
                            <input type="number" step="0.01" min="0" name="amount" placeholder="Amount"
                                class="flex-1 h-11 rounded-2xl border border-gray-200 px-4 text-sm font-semibold
                                           focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition">

                            <button type="submit"
                                class="h-11 px-4 rounded-2xl bg-rose-600 text-white text-xs font-black uppercase tracking-widest hover:bg-rose-700 transition">
                                Deduct
                            </button>
                        </div>
                    </form>

                    {{-- Clear --}}
                    <form action="{{ route('admin.customers.credit.clear', $customer) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="w-full h-11 rounded-2xl bg-gray-100 text-gray-700 text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition">
                            Clear to 0
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
