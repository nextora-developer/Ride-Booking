<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Driver Details</h1>
                <p class="text-sm text-gray-600 mt-1">审核司机资料 / 设置状态</p>
            </div>

            <a href="{{ route('admin.drivers.pending') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm font-semibold">
                ← Back to Pending
            </a>
        </div>

        @if (session('status'))
            <div class="mt-6 p-4 rounded-xl bg-green-50 text-green-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 bg-white rounded-2xl shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-xs text-gray-500">Name</div>
                    <div class="font-semibold text-gray-900">{{ $driver->name }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Email</div>
                    <div class="font-semibold text-gray-900">{{ $driver->email }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Shift</div>
                    <div class="font-semibold text-gray-900">{{ $driver->shift }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Driver Status</div>
                    <div class="font-semibold text-gray-900">{{ $driver->driver_status }}</div>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <form method="POST" action="{{ route('admin.drivers.approve', $driver) }}">
                    @csrf
                    <button class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold">
                        Approve
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.drivers.suspend', $driver) }}">
                    @csrf
                    <button class="px-4 py-2 rounded-lg bg-yellow-600 text-white text-sm font-semibold">
                        Suspend
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.drivers.reject', $driver) }}" class="flex items-center gap-2">
                    @csrf
                    <input type="text" name="reason" placeholder="Reject reason (optional)"
                           class="border rounded-lg px-3 py-2 text-sm">
                    <button class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold">
                        Reject
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>