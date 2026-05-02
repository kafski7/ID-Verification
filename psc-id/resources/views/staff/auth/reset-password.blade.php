<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — PSC Staff Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col">

    <header class="bg-blue-900 text-white px-4 py-3 flex items-center gap-3">
        <div class="w-9 h-9 shrink-0 bg-white/20 rounded-lg flex items-center justify-center text-xs font-bold">PSC</div>
        <div>
            <p class="text-[11px] opacity-75 font-medium">PUBLIC SERVICES COMMISSION</p>
            <p class="text-sm font-bold">Staff Self-Service Portal</p>
        </div>
    </header>

    <main class="flex-1 flex items-start justify-center px-4 py-10">
        <div class="w-full max-w-sm">

            <h1 class="text-xl font-bold text-gray-900 mb-1">Set new password</h1>
            <p class="text-sm text-gray-500 mb-6">Choose a strong password for your account.</p>

            <form method="POST" action="{{ route('staff.password.update') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email', $email ?? '') }}"
                           required autocomplete="email"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                    <input id="password" type="password" name="password"
                           required autocomplete="new-password"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-400 @enderror">
                    <p class="mt-1 text-xs text-gray-400">Minimum 8 characters.</p>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirm new password
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           required autocomplete="new-password"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Set Password & Sign In
                </button>
            </form>

        </div>
    </main>

    <footer class="text-center text-xs text-gray-400 py-4 border-t border-gray-100">
        &copy; {{ date('Y') }} Public Services Commission, Ghana
    </footer>

</body>
</html>
