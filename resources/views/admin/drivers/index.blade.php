<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Pending Driver Approvals</h1>
                <p class="text-sm text-gray-600 mt-1">司机注册后会进入 pending_approval，Admin 在这里审核。</p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
                class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm font-semibold">
                ← Back
            </a>
        </div>

        @if (session('status'))
            <div class="mt-6 p-4 rounded-xl bg-green-50 text-green-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-4 border-b">
                <div class="text-sm text-gray-600">
                    Total pending: <span class="font-semibold text-gray-900">{{ $drivers->total() }}</span>
                </div>
            </div>

            @if ($drivers->count() === 0)
                <div class="p-6 text-sm text-gray-600">
                    ✅ 目前没有待审核司机。
                </div>
            @else
                <div class="divide-y">
                    @foreach ($drivers as $d)
                        <div class="p-4 flex items-center justify-between gap-4">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $d->name }}</div>
                                <div class="text-xs text-gray-600 mt-1">
                                    {{ $d->email }} • Shift: <span class="font-semibold">{{ $d->shift }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.drivers.show', $d) }}"
                                    class="px-3 py-2 rounded-lg bg-black text-white text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t">
                    {{ $drivers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
