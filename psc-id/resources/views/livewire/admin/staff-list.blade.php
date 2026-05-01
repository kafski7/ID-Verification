<div>
    {{-- Search + filter bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search"
               type="search"
               placeholder="Search by name, ID, department, position…"
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <select wire:model.live="status"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All statuses</option>
            <option value="ACTIVE">Active</option>
            <option value="INACTIVE">Inactive</option>
        </select>

        @if(auth()->user()->isHrAdmin())
        <a href="{{ route('admin.staff.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Staff
        </a>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($staff->isEmpty())
            <div class="px-5 py-12 text-center text-sm text-gray-400">No staff members found.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50">
                            <th class="px-5 py-3 text-left font-medium">Staff ID</th>
                            <th class="px-5 py-3 text-left font-medium">Name</th>
                            <th class="px-5 py-3 text-left font-medium">Department</th>
                            <th class="px-5 py-3 text-left font-medium">Position</th>
                            <th class="px-5 py-3 text-left font-medium">Status</th>
                            <th class="px-5 py-3 text-left font-medium">Card Expires</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($staff as $member)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $member->staff_id }}</td>
                                <td class="px-5 py-3 font-medium text-gray-800">{{ $member->full_name }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $member->department }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $member->position }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $member->status === 'ACTIVE' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $member->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs">
                                    {{ $member->card_expires?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.staff.show', $member) }}"
                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $staff->links() }}
            </div>
        @endif
    </div>
</div>
