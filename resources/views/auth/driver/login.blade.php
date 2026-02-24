<x-guest-layout>
    <div class="max-w-md mx-auto mt-12 bg-white shadow rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-center mb-6">
            Driver Login
        </h2>

        <form method="POST" action="{{ route('driver.login.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-6">
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-lg">
                Login as Driver
            </button>
        </form>

        <div class="mt-4 text-center text-sm">
            <a href="{{ route('driver.register') }}"
               class="text-green-600 underline">
                Register as Driver
            </a>
        </div>
    </div>
</x-guest-layout>