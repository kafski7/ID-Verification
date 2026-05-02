<x-layouts.admin :heading="'Edit: ' . $adminUser->name" subheading="Update account details">

    <div class="max-w-lg">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('admin.admin-users.update', $adminUser) }}" novalidate>
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $adminUser->name) }}" required
                           class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $adminUser->email) }}" required
                           class="w-full border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Role --}}
                <div class="mb-5">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="role" name="role" required
                            class="w-full border {{ $errors->has('role') ? 'border-red-400' : 'border-gray-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="SUPER_ADMIN" {{ old('role', $adminUser->role) === 'SUPER_ADMIN' ? 'selected' : '' }}>Super Admin</option>
                        <option value="HR_ADMIN"    {{ old('role', $adminUser->role) === 'HR_ADMIN'    ? 'selected' : '' }}>HR Admin</option>
                        <option value="VIEWER"      {{ old('role', $adminUser->role) === 'VIEWER'      ? 'selected' : '' }}>Viewer</option>
                    </select>
                    @error('role')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Password (optional on edit) --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        New Password
                        <span class="text-gray-400 font-normal">(leave blank to keep current)</span>
                    </label>
                    <input id="password" name="password" type="password"
                           class="w-full border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Password confirm --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.admin-users.index') }}"
                       class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
