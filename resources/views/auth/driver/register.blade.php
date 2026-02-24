<x-guest-layout>
    <div class="max-w-md mx-auto mt-12 bg-white shadow rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-center mb-6">
            Driver Registration
        </h2>

        <form method="POST" action="{{ route('driver.register.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm mb-1">Full Name</label>
                <input type="text" name="name" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-6">
                <label class="block text-sm mb-1">Select Shift</label>
                <select name="shift" required
                        class="w-full border rounded-lg px-3 py-2">
                    <option value="">Choose Shift</option>
                    <option value="day">Day Shift</option>
                    <option value="night">Night Shift</option>
                </select>
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-lg">
                Register as Driver
            </button>
        </form>
    </div>
</x-guest-layout>