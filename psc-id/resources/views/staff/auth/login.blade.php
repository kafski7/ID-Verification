<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login — PSC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col">

    {{-- Header --}}
    <header class="bg-blue-900 text-white px-4 py-3 flex items-center gap-3">
        <div class="w-9 h-9 shrink-0 bg-white/20 rounded-lg flex items-center justify-center text-xs font-bold">PSC</div>
        <div>
            <p class="text-[11px] opacity-75 font-medium">PUBLIC SERVICES COMMISSION</p>
            <p class="text-sm font-bold">Staff Self-Service Portal</p>
        </div>
    </header>

    <main class="flex-1 flex items-start justify-center px-4 py-10">
        <div class="w-full max-w-sm">

            <h1 class="text-xl font-bold text-gray-900 mb-1">Sign in</h1>
            <p class="text-sm text-gray-500 mb-6">Use your PSC registered email address.</p>

            {{-- Status (e.g. after password reset) --}}
            @if(session('status'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('staff.login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="{{ route('staff.password.request') }}"
                           class="text-xs text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-600">Remember me</span>
                </label>

                <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Sign in
                </button>
            </form>

            <p class="mt-6 text-xs text-gray-400 text-center">
                First time? Use <a href="{{ route('staff.password.request') }}" class="text-blue-600 hover:underline">Forgot Password</a>
                to set up your account with your registered email.
            </p>

        </div>
    </main>

    <footer class="text-center text-xs text-gray-400 py-4 border-t border-gray-100">
        &copy; {{ date('Y') }} Public Services Commission, Ghana
    </footer>

</body>
</html>
