@extends('layouts.admin-app')

@section('title', 'Edit Driver')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">
                Edit Driver
            </h1>
            <p class="text-sm text-slate-500 font-semibold mt-1">
                Update driver profile & vehicle details.
            </p>
        </div>

        <div class="shrink-0">
            <a href="{{ route('admin.drivers.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl
                       bg-white border border-gray-200
                       text-xs font-black uppercase tracking-widest
                       hover:bg-gray-50 transition">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                </svg>
                Back
            </a>
        </div>
    </div>
@endsection

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-3">
            <div class="rounded-[2.5rem] bg-white border border-slate-100 shadow-sm p-8">

                <div class="mb-8">
                    <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Driver Settings</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase mt-1">Configure account and vehicle credentials</p>
                </div>

                <form method="POST" action="{{ route('admin.drivers.update', $driver) }}" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-5">
                        {{-- 每个输入框都使用了相同的精致样式：bg-slate-50 + focus:bg-white --}}
                        @php
                            $inputClass =
                                'mt-2 w-full h-12 rounded-2xl border-slate-100 bg-slate-50/50 px-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none';
                            $labelClass = 'text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1';
                        @endphp

                        {{-- Username --}}
                        <div class="sm:col-span-1">
                            <label class="{{ $labelClass }}">Username</label>
                            <input type="text" name="name" value="{{ old('name', $driver->name) }}"
                                class="{{ $inputClass }}">
                        </div>

                        {{-- Full Name --}}
                        <div class="sm:col-span-1">
                            <label class="{{ $labelClass }}">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $driver->full_name) }}"
                                class="{{ $inputClass }}">
                        </div>

                        {{-- IC Number --}}
                        <div>
                            <label class="{{ $labelClass }}">IC Number</label>
                            <input type="text" name="ic_number" value="{{ old('ic_number', $driver->ic_number) }}"
                                class="{{ $inputClass }}">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="{{ $labelClass }}">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $driver->phone) }}"
                                class="{{ $inputClass }}">
                        </div>

                        {{-- Email --}}
                        <div class="sm:col-span-1">
                            <label class="{{ $labelClass }}">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $driver->email) }}"
                                class="{{ $inputClass }}">
                        </div>

                        {{-- Car Plate --}}
                        <div>
                            <label class="{{ $labelClass }}">Car Plate</label>
                            <input type="text" name="car_plate" value="{{ old('car_plate', $driver->car_plate) }}"
                                class="{{ $inputClass }}">
                        </div>

                        {{-- Car Model --}}
                        <div>
                            <label class="{{ $labelClass }}">Car Model</label>
                            <input type="text" name="car_model" value="{{ old('car_model', $driver->car_model) }}"
                                class="{{ $inputClass }}">
                        </div>

                        {{-- Bank Name --}}
                        <div>
                            <label class="{{ $labelClass }}">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $driver->bank_name) }}"
                                class="{{ $inputClass }}">
                        </div>

                        {{-- Bank Account --}}
                        <div>
                            <label class="{{ $labelClass }}">Bank Account</label>
                            <input type="text" name="bank_account"
                                value="{{ old('bank_account', $driver->bank_account) }}" class="{{ $inputClass }}">
                        </div>

                        {{-- Shift --}}
                        <div class="sm:col-span-1">
                            <label class="{{ $labelClass }}">Shift Assignment</label>
                            <select name="shift" class="{{ $inputClass }} appearance-none cursor-pointer">
                                <option value="day" @selected($driver->shift === 'day')>Day Shift (Morning)</option>
                                <option value="night" @selected($driver->shift === 'night')>Night Shift (Evening)</option>
                            </select>
                        </div>
                    </div>

                    {{-- 操作区域 --}}
                    <div class="mt-10 pt-8 border-t border-slate-50 flex items-center gap-4">
                        <button type="submit"
                            class="h-12 px-8 rounded-2xl bg-slate-900 text-white text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-600 shadow-xl shadow-slate-200 transition-all hover:-translate-y-0.5 active:scale-95">
                            Save Changes
                        </button>

                        <a href="{{ route('admin.drivers.index') }}"
                            class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">
                            Discard
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
