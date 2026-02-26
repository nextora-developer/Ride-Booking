@extends('layouts.admin-app')

@section('title', 'Reports')

@section('header')
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">Reports Center</h1>
        <p class="mt-1 text-sm text-slate-500">Financial & operational overview</p>
    </div>
@endsection

@section('content')

    {{-- Date Filter --}}
    <div class="rounded-3xl bg-white border border-gray-100 p-6 shadow-sm mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="text-xs font-bold text-slate-400">From</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                    class="mt-1 h-11 px-4 rounded-2xl border border-gray-200">
            </div>

            <div>
                <label class="text-xs font-bold text-slate-400">To</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                    class="mt-1 h-11 px-4 rounded-2xl border border-gray-200">
            </div>

            <button class="h-11 px-6 rounded-2xl bg-black text-white font-bold">
                Apply
            </button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="rounded-3xl bg-white border p-6 shadow-sm">
            <div class="text-xs uppercase text-slate-400 font-bold">Total Orders</div>
            <div class="mt-2 text-3xl font-extrabold">{{ $totalOrders }}</div>
        </div>

        <div class="rounded-3xl bg-white border p-6 shadow-sm">
            <div class="text-xs uppercase text-slate-400 font-bold">Completed</div>
            <div class="mt-2 text-3xl font-extrabold">{{ $completed }}</div>
        </div>

        <div class="rounded-3xl bg-white border p-6 shadow-sm">
            <div class="text-xs uppercase text-slate-400 font-bold">Pending</div>
            <div class="mt-2 text-3xl font-extrabold">{{ $pending }}</div>
        </div>
    </div>

    {{-- Payment Breakdown --}}
    <div class="rounded-3xl bg-white border p-6 shadow-sm mb-6">
        <div class="text-sm font-extrabold text-slate-900">Payment Breakdown</div>

        <div class="mt-4 grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-2xl font-bold">{{ $cash }}</div>
                <div class="text-xs text-slate-500">Cash</div>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $credit }}</div>
                <div class="text-xs text-slate-500">Credit</div>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $transfer }}</div>
                <div class="text-xs text-slate-500">Transfer</div>
            </div>
        </div>
    </div>

    {{-- Driver Performance --}}
    <div class="rounded-3xl bg-white border shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b">
            <div class="text-sm font-extrabold">Driver Performance (Completed)</div>
        </div>

        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-slate-400 font-bold">
                <tr>
                    <th class="px-6 py-3 text-left">Driver</th>
                    <th class="px-6 py-3 text-left">Completed Trips</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($driverStats as $d)
                    <tr>
                        <td class="px-6 py-4 font-bold">
                            {{ optional($d->driver)->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $d->total }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
