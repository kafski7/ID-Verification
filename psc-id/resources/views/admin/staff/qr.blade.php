<x-layouts.admin heading="QR Code" :subheading="'Token for ' . $staff->full_name">

    {{-- Back link --}}
    <div class="mb-6">
        <a href="{{ route('admin.staff.show', $staff) }}"
           class="inline-flex items-center text-sm text-gray-400 hover:text-white transition">
            &larr; Back to Staff Profile
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-900/40 border border-green-700 text-green-300 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- QR Card --}}
        <div class="bg-gray-800 rounded-2xl p-8 flex flex-col items-center gap-6 shadow-lg">
            <h2 class="text-lg font-semibold text-white">Verification QR Code</h2>

            <div class="bg-white p-4 rounded-xl shadow-inner">
                <img src="{{ $qrDataUri }}" alt="QR Code for {{ $staff->full_name }}"
                     class="w-64 h-64 block" />
            </div>

            <p class="text-xs text-gray-500 text-center">
                Scan to verify identity at <span class="text-gray-300">{{ config('app.url') }}</span>
            </p>

            {{-- Token info --}}
            <div class="w-full text-sm text-gray-400 space-y-1 border-t border-gray-700 pt-4">
                <div class="flex justify-between">
                    <span>Issued</span>
                    <span class="text-gray-200">{{ $token->issued_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Status</span>
                    @if ($token->revoked)
                        <span class="text-red-400 font-medium">Revoked</span>
                    @else
                        <span class="text-green-400 font-medium">Active</span>
                    @endif
                </div>
                @if ($token->expires_at)
                    <div class="flex justify-between">
                        <span>Expires</span>
                        <span class="text-gray-200">{{ $token->expires_at->format('d M Y') }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Actions & URL --}}
        <div class="space-y-6">

            {{-- Verification URL --}}
            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Verification URL</h3>
                <div class="bg-gray-900 rounded-lg px-4 py-3 break-all text-xs text-indigo-300 font-mono select-all">
                    {{ $url }}
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    This URL is embedded in the QR code. Anyone who scans it can verify the cardholder's identity.
                </p>
            </div>

            {{-- HR Admin actions --}}
            @if (auth()->user()->isHrAdmin())
                <div class="bg-gray-800 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Token Management</h3>

                    <div class="space-y-3">

                        {{-- Regenerate --}}
                        <form id="form-regenerate-qr"
                              method="POST" action="{{ route('admin.staff.qr.regenerate', $staff) }}">
                            @csrf
                            @method('PATCH')
                            <button type="button"
                                    @click="$dispatch('open-confirm-modal', {
                                        title: 'Regenerate QR Token',
                                        message: 'This will invalidate the current QR code and generate a new one.',
                                        formId: 'form-regenerate-qr',
                                        confirmLabel: 'Regenerate'
                                    })"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2.5 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Regenerate QR Token
                            </button>
                        </form>

                        {{-- Revoke --}}
                        @unless ($token->revoked)
                            <form id="form-revoke-qr"
                                  method="POST" action="{{ route('admin.staff.qr.revoke', $staff) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        @click="$dispatch('open-confirm-modal', {
                                            title: 'Revoke Token',
                                            message: 'This will permanently revoke the current QR code. The staff member cannot be verified until a new token is generated.',
                                            formId: 'form-revoke-qr',
                                            confirmLabel: 'Revoke'
                                        })"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-700 hover:bg-red-600 text-white text-sm font-medium px-5 py-2.5 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Revoke Token
                                </button>
                            </form>
                        @endunless

                    </div>

                    <p class="text-xs text-gray-500 mt-4">
                        Regenerating or revoking a token immediately invalidates any printed cards bearing the old QR code.
                    </p>
                </div>
            @endif

            {{-- Staff summary --}}
            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Staff Summary</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Name</dt>
                        <dd class="text-white font-medium">{{ $staff->full_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Staff ID</dt>
                        <dd class="text-white font-mono">{{ $staff->staff_id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Position</dt>
                        <dd class="text-white">{{ $staff->position }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Department</dt>
                        <dd class="text-white">{{ $staff->department }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Card Expires</dt>
                        <dd class="{{ $staff->card_expires?->isPast() ? 'text-red-400' : 'text-white' }}">
                            {{ $staff->card_expires?->format('d M Y') ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>

</x-layouts.admin>
