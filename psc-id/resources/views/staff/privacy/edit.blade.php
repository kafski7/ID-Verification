<x-layouts.staff heading="Privacy Settings">

    <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
        <p class="text-sm text-gray-500 mb-1">
            Control which details are visible when someone scans your QR code on the public verification page.
        </p>
        <p class="text-xs text-gray-400">
            Fields like your name, ID No, position, department and card status are always shown and cannot be hidden.
        </p>
    </div>

    <form method="POST" action="{{ route('staff.privacy.update') }}">
        @csrf
        @method('PATCH')

        <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100 mb-4">

            @php
                $settings = $staff->privacy_settings ?? [];
                $fields = [
                    ['hide_staff_id',       'Staff ID',       'Your internal staff identification number'],
                    ['hide_grade',          'Grade',          'Your job grade / salary band'],
                    ['hide_phone',          'Telephone',      'Your phone number'],
                    ['hide_email',          'Email',          'Your email address'],
                    ['hide_other_contacts', 'Other Contacts', 'Any additional contact information'],
                ];
            @endphp

            @foreach($fields as [$key, $label, $desc])
            @php $showKey = 'show_' . str_replace('hide_', '', $key); @endphp
            <label class="flex items-center justify-between gap-4 px-5 py-4 cursor-pointer"
                   for="{{ $showKey }}">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">{{ $label }}</p>
                    <p class="text-xs text-gray-500">{{ $desc }}</p>
                </div>

                {{-- Toggle switch: ON = visible on verify page --}}
                <div class="relative shrink-0" x-data>
                    <input type="checkbox" id="{{ $showKey }}" name="{{ $showKey }}" value="1"
                           {{ !($settings[$key] ?? false) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 rounded-full transition peer-checked:bg-blue-600"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform
                                peer-checked:translate-x-5"></div>
                </div>
            </label>
            @endforeach

        </div>

        <div class="flex gap-3">
            <a href="{{ route('staff.portal') }}"
               class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition">
                Save Settings
            </button>
        </div>

    </form>

</x-layouts.staff>
