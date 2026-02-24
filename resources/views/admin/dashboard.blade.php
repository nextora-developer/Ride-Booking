<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto px-4">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>

        <div class="mt-6 bg-white rounded-2xl shadow p-6">
            <p class="text-gray-600">
                👑 Boss 控制台
            </p>

            <div class="mt-4 text-sm space-y-2">
                <div><strong>Role:</strong> {{ auth()->user()->role }}</div>
                <div><strong>Email:</strong> {{ auth()->user()->email }}</div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="#" class="p-4 bg-gray-100 rounded-xl hover:bg-gray-200">
                    🚗 全部订单
                </a>
                <a href="#" class="p-4 bg-gray-100 rounded-xl hover:bg-gray-200">
                    👨‍✈️ 司机管理
                </a>
                <a href="#" class="p-4 bg-gray-100 rounded-xl hover:bg-gray-200">
                    📊 报表
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
