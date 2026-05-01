<x-layouts.admin :heading="'Edit: ' . $staff->full_name">

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.staff.update', $staff) }}" enctype="multipart/form-data"
              class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Row 1 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Staff ID <span class="text-red-500">*</span></label>
                    <input type="text" name="staff_id" value="{{ old('staff_id', $staff->staff_id) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('staff_id') border-red-400 @enderror">
                    @error('staff_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID No <span class="text-red-500">*</span></label>
                    <input type="text" name="id_no" value="{{ old('id_no', $staff->id_no) }}"
                           placeholder="e.g. 123456789"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_no') border-red-400 @enderror">
                    @error('id_no')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $staff->full_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('full_name') border-red-400 @enderror">
                    @error('full_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sex <span class="text-red-500">*</span></label>
                    <select name="sex" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sex') border-red-400 @enderror">
                        <option value="">— Select —</option>
                        <option value="M" @selected(old('sex', $staff->sex) === 'M')>Male</option>
                        <option value="F" @selected(old('sex', $staff->sex) === 'F')>Female</option>
                    </select>
                    @error('sex')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position <span class="text-red-500">*</span></label>
                    <input type="text" name="position" value="{{ old('position', $staff->position) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('position') border-red-400 @enderror">
                    @error('position')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Job Grade <span class="text-red-500">*</span></label>
                    <input type="text" name="job_grade" value="{{ old('job_grade', $staff->job_grade) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('job_grade') border-red-400 @enderror">
                    @error('job_grade')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Row 3 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                <input type="text" name="department" value="{{ old('department', $staff->department) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('department') border-red-400 @enderror">
                @error('department')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Row 4 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $staff->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Other Contacts --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Other Contacts</label>
                <input type="text" name="other_contacts" value="{{ old('other_contacts', $staff->other_contacts) }}"
                       placeholder="e.g. Tel: 0244000001 / alt@email.com"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-400">Additional contact information that will appear on the printed card.</p>
            </div>

            {{-- Row 5 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Issue <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_issue" value="{{ old('date_of_issue', $staff->date_of_issue?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_of_issue') border-red-400 @enderror">
                    @error('date_of_issue')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Expires <span class="text-red-500">*</span></label>
                    <input type="date" name="card_expires" value="{{ old('card_expires', $staff->card_expires?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('card_expires') border-red-400 @enderror">
                    @error('card_expires')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Row 6 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="ACTIVE" @selected(old('status', $staff->status) === 'ACTIVE')>Active</option>
                        <option value="INACTIVE" @selected(old('status', $staff->status) === 'INACTIVE')>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Replace Photo
                        @if($staff->photo_path)
                            <span class="text-xs text-gray-400 font-normal">(leave blank to keep existing)</span>
                        @endif
                    </label>
                    <input type="file" name="photo" accept="image/jpeg,image/png"
                           class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('photo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.staff.show', $staff) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>

</x-layouts.admin>
