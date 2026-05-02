<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PSC ID' }} — Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex bg-gray-100" x-data="{ sidebarOpen: false }">

    {{-- ── Mobile backdrop ── --}}
    <div x-show="sidebarOpen"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-30 md:hidden"
         style="display:none"></div>

    {{-- ── Sidebar ── --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white flex flex-col transform transition-transform duration-200 md:relative md:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

        {{-- Logo / app name --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-800">
            <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center text-xs font-bold shadow-lg">PSC</div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold tracking-wide truncate">PSC ID System</p>
                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Verification Portal</p>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto">

            <p class="px-3 pt-2 pb-1 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Main</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5-8h2a2 2 0 012 2v5a2 2 0 01-2 2H4a2 2 0 01-2-2v-5a2 2 0 012-2h2"/>
                </svg>
                Dashboard
            </a>

            @if(auth()->user()?->isHrAdmin() && Route::has('admin.staff.index'))
            <a href="{{ route('admin.staff.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.staff.*') ? 'bg-blue-600 text-white shadow' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                Staff
            </a>
            @endif

            @if(Route::has('admin.scan-logs.index'))
            <a href="{{ route('admin.scan-logs.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.scan-logs.*') ? 'bg-blue-600 text-white shadow' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                </svg>
                Scan Logs
            </a>
            @endif

            @if(auth()->user()?->isSuperAdmin() && Route::has('admin.admin-users.index'))
                <p class="px-3 pt-4 pb-1 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Administration</p>

                <a href="{{ route('admin.admin-users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.admin-users.*') ? 'bg-blue-600 text-white shadow' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                    Admin Users
                </a>
            @endif
        </nav>

        {{-- User info --}}
        <div class="px-4 py-4 border-t border-gray-800 bg-gray-950/40">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-xs font-bold uppercase shadow">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-gray-400 truncate uppercase tracking-wide">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 text-xs text-gray-300 hover:text-white bg-gray-800 hover:bg-red-600 transition py-2 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main content area ── --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-auto">

        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3 min-w-0">
                <button @click="sidebarOpen = true"
                        class="md:hidden text-gray-600 hover:text-gray-900 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h1 class="text-lg font-semibold text-gray-800 truncate">{{ $heading ?? 'Dashboard' }}</h1>
                    @isset($subheading)
                        <p class="text-sm text-gray-500 mt-0.5 truncate">{{ $subheading }}</p>
                    @endisset
                </div>
            </div>
            <div class="hidden sm:block text-xs text-gray-400 shrink-0 ml-4">{{ now()->format('D, d M Y') }}</div>
        </header>

        {{-- Page body --}}
        <main class="flex-1 p-4 sm:p-6">
            {{ $slot }}
        </main>

    </div>

    <x-confirm-modal />

</body>
</html>
