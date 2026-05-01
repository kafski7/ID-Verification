<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffStoreRequest;
use App\Http\Requests\StaffUpdateRequest;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        return view('admin.staff.index');
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(StaffStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $this->storePhoto($request);
        }
        unset($data['photo']);

        Staff::create($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    public function show(Staff $staff): View
    {
        return view('admin.staff.show', compact('staff'));
    }

    public function edit(Staff $staff): View
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(StaffUpdateRequest $request, Staff $staff): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($staff->photo_path) {
                Storage::disk('private')->delete($staff->photo_path);
            }
            $data['photo_path'] = $this->storePhoto($request);
        }
        unset($data['photo']);

        $staff->update($data);

        return redirect()->route('admin.staff.show', $staff)
            ->with('success', 'Staff member updated successfully.');
    }

    public function deactivate(Staff $staff): RedirectResponse
    {
        $this->authorizeHrAdmin();

        $staff->update(['status' => 'INACTIVE']);

        return redirect()->route('admin.staff.show', $staff)
            ->with('success', 'Staff member deactivated.');
    }

    /**
     * Store an uploaded photo securely in the private disk.
     * Returns the stored relative path.
     */
    private function storePhoto(Request $request): string
    {
        $file = $request->file('photo');
        $safeName = hash('sha256', uniqid('', true)) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs('photos', $safeName, 'private');
    }

    /**
     * Abort with 403 if the authenticated user is not HR_ADMIN or SUPER_ADMIN.
     */
    private function authorizeHrAdmin(): void
    {
        if (! request()->user()?->isHrAdmin()) {
            abort(403, 'Unauthorised.');
        }
    }
}
