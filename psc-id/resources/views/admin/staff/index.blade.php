<x-layouts.admin heading="Staff" subheading="All staff members">

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <livewire:admin.staff-list />

</x-layouts.admin>
