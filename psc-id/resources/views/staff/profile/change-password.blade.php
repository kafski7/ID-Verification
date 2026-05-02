<x-layouts.staff heading="Change Password">

    <div class="bg-white rounded-xl shadow-sm p-5">

        <form method="POST" action="{{ route('staff.password.change') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Current password
                </label>
                <input id="current_password" type="password" name="current_password"
                       required autocomplete="current-password"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-400 @enderror">
                @error('current_password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    New password
                </label>
                <input id="password" type="password" name="password"
                       required autocomplete="new-password"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-400 @enderror">
                <p class="mt-1 text-xs text-gray-400">Minimum 8 characters.</p>
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm new password
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="flex gap-3 pt-1">
                <a href="{{ route('staff.portal') }}"
                   class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition">
                    Change Password
                </button>
            </div>

        </form>
    </div>

</x-layouts.staff>
