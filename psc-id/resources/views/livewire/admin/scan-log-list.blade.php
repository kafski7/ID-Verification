<div>
    {{-- Filter bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5 flex-wrap">
        <input wire:model.live.debounce.300ms="search"
               type="search"
               placeholder="Search by staff name, ID, or IP address…"
               class="flex-1 min-w-[200px] border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <select wire:model.live="result"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All results</option>
            <option value="VALID">Valid</option>
            <option value="INVALID">Invalid</option>
        </select>

        <input wire:model.live="dateFrom"
               type="date"
               title="From date"
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <input wire:model.live="dateTo"
               type="date"
               title="To date"
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($logs->isEmpty())
            <div class="px-5 py-12 text-center text-sm text-gray-400">No scan log entries found.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50">
                            <th class="px-5 py-3 text-left font-medium">Date / Time</th>
                            <th class="px-5 py-3 text-left font-medium">Staff</th>
                            <th class="px-5 py-3 text-left font-medium">Staff ID</th>
                            <th class="px-5 py-3 text-left font-medium">IP Address</th>
                            <th class="px-5 py-3 text-left font-medium">Result</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3 text-gray-500 whitespace-nowrap">
                                    {{ $log->scanned_at->format('d M Y, H:i:s') }}
                                </td>
                                <td class="px-5 py-3 font-medium text-gray-800">
                                    @if($log->staff)
                                        <a href="{{ route('admin.staff.show', $log->staff) }}"
                                           class="hover:text-blue-600 transition">
                                            {{ $log->staff->full_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-gray-500">
                                    {{ $log->staff?->staff_id ?? '—' }}
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-gray-500">
                                    {{ $log->ip_address ?? '—' }}
                                </td>
                                <td class="px-5 py-3">
                                    @if($log->result === 'VALID')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            ✓ Valid
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            ✗ Invalid
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- Summary --}}
    <p class="mt-3 text-xs text-gray-400">
        Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ number_format($logs->total()) }} entries
    </p>
</div>
