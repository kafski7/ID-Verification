<x-layouts.admin heading="Admin Users" subheading="Manage portal accounts and roles">

    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-end mb-5">
        <a href="{{ route('admin.admin-users.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Admin User
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50">
                        <th class="px-5 py-3 text-left font-medium">Name</th>
                        <th class="px-5 py-3 text-left font-medium">Email</th>
                        <th class="px-5 py-3 text-left font-medium">Role</th>
                        <th class="px-5 py-3 text-left font-medium">Created</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 font-medium text-gray-800">
                                {{ $user->name }}
                                @if($user->is(auth()->user()))
                                    <span class="ml-2 text-xs text-gray-400">(you)</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $roleColour = match($user->role) {
                                        'SUPER_ADMIN' => 'bg-purple-100 text-purple-700',
                                        'HR_ADMIN'    => 'bg-blue-100 text-blue-700',
                                        'INACTIVE'    => 'bg-red-100 text-red-700',
                                        default       => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $roleColour }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.admin-users.edit', $user) }}"
                                       class="text-xs text-blue-600 hover:underline">Edit</a>

                                    @if(! $user->is(auth()->user()) && $user->role !== 'INACTIVE')
                                        <form method="POST"
                                              action="{{ route('admin.admin-users.deactivate', $user) }}"
                                              onsubmit="return confirm('Deactivate {{ addslashes($user->name) }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-xs text-red-500 hover:underline">
                                                Deactivate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
