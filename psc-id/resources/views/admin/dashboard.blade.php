<x-layouts.admin heading="Dashboard" subheading="Overview of PSC ID activity">

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Active Staff --}}
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['active_staff'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Active Staff</p>
            </div>
        </div>

        {{-- Inactive Staff --}}
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['inactive_staff'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Inactive Staff</p>
            </div>
        </div>

        {{-- Scans Today --}}
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75V16.5zM16.5 6.75h.75v.75h-.75v-.75z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['scans_today'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Scans Today</p>
            </div>
        </div>

        {{-- Valid Scans Today --}}
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['valid_today'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Valid Scans Today</p>
            </div>
        </div>

    </div>

    {{-- Recent Scan Logs --}}
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Recent Scan Activity</h2>
        </div>

        @if($recentScans->isEmpty())
            <div class="px-5 py-10 text-center text-sm text-gray-400">No scan activity yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                            <th class="px-5 py-3 text-left font-medium">Staff</th>
                            <th class="px-5 py-3 text-left font-medium">Result</th>
                            <th class="px-5 py-3 text-left font-medium">IP Address</th>
                            <th class="px-5 py-3 text-left font-medium">Scanned At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentScans as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3">
                                    @if($log->staff)
                                        <div class="font-medium text-gray-800">{{ $log->staff->full_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $log->staff->staff_id }}</div>
                                    @else
                                        <span class="text-gray-400 italic">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $badge = match($log->result) {
                                            'VALID'     => 'bg-green-100 text-green-700',
                                            'EXPIRED'   => 'bg-yellow-100 text-yellow-700',
                                            'REVOKED'   => 'bg-orange-100 text-orange-700',
                                            'INVALID'   => 'bg-red-100 text-red-700',
                                            default     => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badge }}">
                                        {{ $log->result }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $log->ip_address ?? '—' }}</td>
                                <td class="px-5 py-3 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($log->scanned_at)->format('d M Y, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layouts.admin>
