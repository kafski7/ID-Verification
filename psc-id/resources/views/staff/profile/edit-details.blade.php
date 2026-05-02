<x-layouts.staff heading="Edit My Details">

    <div class="bg-white rounded-xl shadow-sm p-5">

        <p class="text-sm text-gray-500 mb-5">
            You can update your contact information here. Name, position, department, and other HR-managed fields
            must be changed by your HR Administrator.
        </p>

        <form method="POST" action="{{ route('staff.profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input id="email" type="email" name="email"
                       value="{{ old('email', $staff->email) }}"
                       required autocomplete="email"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telephone</label>
                <input id="phone" type="tel" name="phone"
                       value="{{ old('phone', $staff->phone) }}"
                       autocomplete="tel" maxlength="30"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-400 @enderror"
                       placeholder="e.g. +233 24 000 0000">
                @error('phone')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Other contacts --}}
            <div>
                <label for="other_contacts" class="block text-sm font-medium text-gray-700 mb-1">
                    Other contacts
                </label>
                <textarea id="other_contacts" name="other_contacts" rows="3" maxlength="500"
                          class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('other_contacts') border-red-400 @enderror"
                          placeholder="WhatsApp, alternative phone, etc.">{{ old('other_contacts', $staff->other_contacts) }}</textarea>
                @error('other_contacts')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-1">
                <a href="{{ route('staff.portal') }}"
                   class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition">
                    Save Changes
                </button>
            </div>

        </form>
    </div>

</x-layouts.staff>
