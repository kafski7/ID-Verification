<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Staff Portal' }} — PSC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col" x-data="{ navOpen: false }">

    {{-- ── Header ── --}}
    <header class="sticky top-0 z-30 bg-blue-900 text-white shadow-md">
        <div class="flex items-center gap-3 px-4 py-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-9 h-9 shrink-0 bg-white/20 rounded-lg flex items-center justify-center text-xs font-bold">PSC</div>
                <div class="min-w-0">
                    <p class="text-[11px] opacity-75 font-medium">PSC ID System</p>
                    <p class="text-sm font-bold truncate">Staff Portal</p>
                </div>
            </div>
            <span class="hidden sm:block text-xs opacity-75 truncate max-w-[140px]">
                {{ auth('staff')->user()->full_name }}
            </span>
            <button @click="navOpen = !navOpen"
                    class="ml-2 p-1.5 rounded-lg text-white/75 hover:text-white hover:bg-white/10 transition"
                    aria-label="Toggle menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Nav (collapsible on mobile, always visible on sm+) --}}
        <nav :class="navOpen ? 'block' : 'hidden sm:flex'"
             class="border-t border-white/20 sm:flex sm:items-center sm:gap-1 px-3 pb-1 sm:pb-0">

            @php $navLink = 'flex items-center gap-1.5 px-3 py-2 sm:py-1.5 text-sm rounded-lg transition text-white/80 hover:text-white hover:bg-white/10'; @endphp

            <a href="{{ route('staff.portal') }}"
               class="{{ $navLink }} {{ request()->routeIs('staff.portal') ? 'bg-white/15 text-white font-semibold' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                My Profile
            </a>

            <a href="{{ route('staff.profile.edit') }}"
               class="{{ $navLink }} {{ request()->routeIs('staff.profile.edit') ? 'bg-white/15 text-white font-semibold' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Details
            </a>

            <a href="{{ route('staff.password.edit') }}"
               class="{{ $navLink }} {{ request()->routeIs('staff.password.edit') ? 'bg-white/15 text-white font-semibold' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Password
            </a>

            <a href="{{ route('staff.privacy.edit') }}"
               class="{{ $navLink }} {{ request()->routeIs('staff.privacy.edit') ? 'bg-white/15 text-white font-semibold' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Privacy
            </a>

            <div class="flex-1 hidden sm:block"></div>

            <form method="POST" action="{{ route('staff.logout') }}" class="sm:ml-2">
                @csrf
                <button type="submit"
                        class="{{ $navLink }} w-full sm:w-auto text-red-300 hover:text-red-100 hover:bg-red-900/30">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign Out
                </button>
            </form>

        </nav>
    </header>

    {{-- ── Page body ── --}}
    <main class="flex-1 px-4 py-6 w-full max-w-2xl mx-auto">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-5 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @isset($heading)
            <h1 class="text-lg font-bold text-gray-900 mb-5">{{ $heading }}</h1>
        @endisset

        {{ $slot }}
    </main>

    <x-confirm-modal />

</body>
</html>
