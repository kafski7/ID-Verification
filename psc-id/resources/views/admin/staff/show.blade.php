<x-layouts.admin :heading="$staff->full_name" :subheading="$staff->staff_id">

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Photo + actions --}}
        <div class="space-y-4">
            {{-- Photo --}}
            <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col items-center gap-3">
                @if($staff->photo_path)
                    <img src="{{ route('admin.staff.photo', $staff) }}"
                         alt="Photo of {{ $staff->full_name }}"
                         class="w-32 h-32 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center text-3xl font-bold text-blue-400">
                        {{ strtoupper(substr($staff->full_name, 0, 1)) }}
                    </div>
                @endif

                <div class="text-center">
                    <p class="font-semibold text-gray-800">{{ $staff->full_name }}</p>
                    <p class="text-sm text-gray-500">{{ $staff->position }}</p>
                </div>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    {{ $staff->status === 'ACTIVE' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $staff->status }}
                </span>
            </div>

            {{-- Actions --}}
            @if(auth()->user()->isHrAdmin())
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-2">
                <a href="{{ route('admin.staff.edit', $staff) }}"
                   class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Edit Staff Record
                </a>

                @if($staff->status === 'ACTIVE')
                <form method="POST" action="{{ route('admin.staff.deactivate', $staff) }}"
                      onsubmit="return confirm('Deactivate {{ addslashes($staff->full_name) }}? This will not delete the record.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="flex items-center justify-center gap-2 w-full border border-red-300 text-red-600 hover:bg-red-50 text-sm font-medium px-4 py-2 rounded-lg transition">
                        Deactivate
                    </button>
                </form>
                @endif
            </div>
            @endif

            {{-- QR Code link (visible to all authenticated roles) --}}
            @if(Route::has('admin.staff.qr.show'))
            <div class="bg-white rounded-xl shadow-sm p-5">
                <a href="{{ route('admin.staff.qr.show', $staff) }}"
                   class="flex items-center justify-center gap-2 w-full bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    View QR Code
                </a>
            </div>
            @endif

            {{-- ID Card preview + PDF --}}
            @if(Route::has('admin.staff.card.show'))
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-2">
                <a href="{{ route('admin.staff.card.show', $staff) }}"
                   class="flex items-center justify-center gap-2 w-full bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/>
                    </svg>
                    Preview ID Card
                </a>
                <a href="{{ route('admin.staff.card.pdf', $staff) }}"
                   class="flex items-center justify-center gap-2 w-full border border-red-300 text-red-600 hover:bg-red-50 text-sm font-medium px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download PDF
                </a>
            </div>
            @endif
        </div>

        {{-- Right: Details --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Staff Details</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Staff ID</dt>
                        <dd class="mt-0.5 font-mono text-gray-800">{{ $staff->staff_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">ID No</dt>
                        <dd class="mt-0.5 font-mono text-gray-800">{{ $staff->id_no ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Sex</dt>
                        <dd class="mt-0.5 text-gray-800">{{ $staff->sex === 'M' ? 'Male' : ($staff->sex === 'F' ? 'Female' : '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Department</dt>
                        <dd class="mt-0.5 text-gray-800">{{ $staff->department }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Job Grade</dt>
                        <dd class="mt-0.5 text-gray-800">{{ $staff->job_grade }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Phone</dt>
                        <dd class="mt-0.5 text-gray-800">{{ $staff->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Email</dt>
                        <dd class="mt-0.5 text-gray-800">{{ $staff->email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Other Contacts</dt>
                        <dd class="mt-0.5 text-gray-800">{{ $staff->other_contacts ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Date of Issue</dt>
                        <dd class="mt-0.5 text-gray-800">{{ $staff->date_of_issue?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Card Expires</dt>
                        <dd class="mt-0.5 text-gray-800">{{ $staff->card_expires?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">UUID</dt>
                        <dd class="mt-0.5 font-mono text-xs text-gray-400">{{ $staff->uuid }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Recent scans --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Recent Scan Activity</h2>
                @php $recentScans = $staff->scanLogs()->orderByDesc('scanned_at')->limit(5)->get(); @endphp
                @if($recentScans->isEmpty())
                    <p class="text-sm text-gray-400">No scan activity recorded.</p>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                                <th class="pb-2 text-left">Result</th>
                                <th class="pb-2 text-left">IP</th>
                                <th class="pb-2 text-left">Scanned At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentScans as $log)
                            <tr>
                                <td class="py-2">
                                    @php
                                        $badge = match($log->result) {
                                            'VALID'   => 'bg-green-100 text-green-700',
                                            'EXPIRED' => 'bg-yellow-100 text-yellow-700',
                                            'REVOKED' => 'bg-orange-100 text-orange-700',
                                            'INVALID' => 'bg-red-100 text-red-700',
                                            default   => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badge }}">{{ $log->result }}</span>
                                </td>
                                <td class="py-2 font-mono text-xs text-gray-500">{{ $log->ip_address ?? '—' }}</td>
                                <td class="py-2 text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->scanned_at)->format('d M Y, H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.staff.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Staff List</a>
    </div>

</x-layouts.admin>
