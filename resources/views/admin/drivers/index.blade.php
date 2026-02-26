@extends('layouts.admin-app')

@section('title', 'Drivers')

@section('header')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Drivers</h1>
            <p class="mt-1 text-sm text-slate-500 font-semibold">Manage drivers, shifts, and activity.</p>
        </div>
    </div>
@endsection

@section('content')
    {{-- Search + Shift --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.drivers.index') }}"
              class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                <div class="relative w-full sm:w-96">
                    <input name="q" value="{{ $q ?? '' }}" type="text"
                        placeholder="Search name / email / phone..."
                        class="w-full h-11 rounded-2xl border border-gray-200 bg-white px-4 pr-10 text-sm font-semibold
                               focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                    <svg class="h-5 w-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35" />
                        <circle cx="11" cy="11" r="7" />
                    </svg>
                </div>

                <select name="shift"
                    class="w-full sm:w-44 h-11 rounded-2xl border border-gray-200 bg-white px-4 text-sm font-extrabold
                           focus:ring-4 focus:ring-black/5 focus:border-black outline-none">
                    <option value="">All shifts</option>
                    <option value="day" @selected(($shift ?? '') === 'day')>Day</option>
                    <option value="night" @selected(($shift ?? '') === 'night')>Night</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button class="h-11 px-5 rounded-2xl bg-black text-white text-sm font-extrabold hover:bg-slate-900 transition">
                    Apply
                </button>

                @if(!empty($q) || !empty($shift))
                    <a href="{{ route('admin.drivers.index') }}"
                       class="py-3 px-5 rounded-2xl bg-white border border-gray-200 text-slate-900 text-sm font-extrabold hover:bg-gray-50 transition">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="text-sm font-extrabold text-slate-900">
                Driver List
                <span class="ml-2 text-xs font-black text-slate-500">{{ $drivers->total() }} total</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-black uppercase tracking-widest text-slate-400">
                        <th class="px-5 sm:px-6 py-3">Driver</th>
                        <th class="px-5 sm:px-6 py-3">Contact</th>
                        <th class="px-5 sm:px-6 py-3">Shift</th>
                        <th class="px-5 sm:px-6 py-3">Joined</th>
                        <th class="px-5 sm:px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drivers as $d)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 sm:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-2xl bg-black text-white flex items-center justify-center font-extrabold">
                                        {{ strtoupper(mb_substr($d->name ?? 'D', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-slate-900 truncate">{{ $d->name }}</div>
                                        <div class="text-xs text-slate-500 font-semibold truncate">ID: {{ $d->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 sm:px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">{{ $d->phone ?? '—' }}</div>
                                <div class="text-xs text-slate-500 font-semibold">{{ $d->email ?? '—' }}</div>
                            </td>

                            <td class="px-5 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black
                                    {{ ($d->shift ?? '') === 'day' ? 'bg-blue-50 text-blue-700' : (($d->shift ?? '') === 'night' ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ strtoupper($d->shift ?? '—') }}
                                </span>
                            </td>

                            <td class="px-5 sm:px-6 py-4">
                                <div class="text-sm font-semibold text-slate-900">{{ optional($d->created_at)->format('d M Y') }}</div>
                                <div class="text-xs text-slate-500 font-semibold">{{ optional($d->created_at)->format('h:i A') }}</div>
                            </td>

                            <td class="px-5 sm:px-6 py-4 text-right">
                                <a href="{{ route('admin.drivers.show', $d) }}"
                                   class="inline-flex items-center justify-center h-10 px-4 rounded-2xl bg-white border border-gray-200 text-sm font-extrabold hover:bg-gray-50 transition">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 sm:px-6 py-10 text-center text-sm text-slate-500 font-semibold">
                                No drivers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 sm:px-6 py-4 border-t border-gray-100">
            {{ $drivers->links() }}
        </div>
    </div>
@endsection