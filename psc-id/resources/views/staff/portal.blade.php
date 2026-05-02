<x-layouts.staff heading="My Profile">

    {{-- ── Photo + name banner ── --}}
    <div class="bg-gradient-to-br from-blue-800 to-blue-600 rounded-xl p-5 text-white mb-4 flex items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold shrink-0 overflow-hidden border-2 border-white/30">
            @if($staff->photo_path)
                <img src="{{ route('admin.staff.photo', $staff) }}" alt="" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($staff->full_name, 0, 1)) }}
            @endif
        </div>
        <div class="min-w-0">
            <p class="text-xs opacity-75 font-medium uppercase tracking-wide">{{ $staff->department }}</p>
            <p class="text-lg font-bold leading-tight truncate">{{ $staff->full_name }}</p>
            <p class="text-sm opacity-80 truncate">{{ $staff->position }}</p>
        </div>
    </div>

    {{-- ── Details card ── --}}
    <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100 mb-4">

        @php
            $row = fn(string $label, $value) => [$label, $value];
            $rows = [
                ['Staff ID',   $staff->staff_id],
                ['ID No',      $staff->id_no],
                ['Sex',        $staff->sex === 'M' ? 'Male' : ($staff->sex === 'F' ? 'Female' : null)],
                ['Grade',      $staff->job_grade],
                ['Date of Issue', $staff->date_of_issue?->format('d M Y')],
                ['Card Expires',  $staff->card_expires?->format('d M Y')],
                ['Telephone',  $staff->phone],
                ['Email',      $staff->email],
                ['Other Contacts', $staff->other_contacts],
            ];
        @endphp

        @foreach($rows as [$label, $value])
            @if($value)
            <div class="flex items-start gap-3 px-4 py-3">
                <span class="text-xs text-gray-400 uppercase tracking-wide w-28 shrink-0 pt-0.5">{{ $label }}</span>
                <span class="text-sm text-gray-800 flex-1 break-all">{{ $value }}</span>
            </div>
            @endif
        @endforeach

        <div class="flex items-center gap-3 px-4 py-3">
            <span class="text-xs text-gray-400 uppercase tracking-wide w-28 shrink-0">Status</span>
            <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full
                {{ $staff->status === 'ACTIVE' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $staff->status }}
            </span>
        </div>

    </div>

    {{-- ── Quick actions ── --}}
    <div class="space-y-2">
        <a href="{{ route('staff.profile.edit') }}"
           class="flex items-center justify-between w-full bg-white border border-gray-200 hover:border-blue-400 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 hover:text-blue-700 transition shadow-sm">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit My Details
            </span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('staff.password.edit') }}"
           class="flex items-center justify-between w-full bg-white border border-gray-200 hover:border-blue-400 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 hover:text-blue-700 transition shadow-sm">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Change Password
            </span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('staff.privacy.edit') }}"
           class="flex items-center justify-between w-full bg-white border border-gray-200 hover:border-blue-400 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 hover:text-blue-700 transition shadow-sm">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Privacy Settings
            </span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</x-layouts.staff>
