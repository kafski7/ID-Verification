<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return view('staff.portal', ['staff' => Auth::guard('staff')->user()]);
    }

    public function editDetails()
    {
        return view('staff.profile.edit-details', ['staff' => Auth::guard('staff')->user()]);
    }

    public function updateDetails(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        $data = $request->validate([
            'phone'          => ['nullable', 'string', 'max:30'],
            'email'          => ['required', 'email', 'max:255', 'unique:staff,email,' . $staff->uuid . ',uuid'],
            'other_contacts' => ['nullable', 'string', 'max:500'],
        ]);

        $staff->update($data);

        return redirect()->route('staff.portal')->with('success', 'Your details have been updated.');
    }

    public function editPassword()
    {
        return view('staff.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed', Password::defaults()],
        ]);

        $staff = Auth::guard('staff')->user();

        if (! Hash::check($request->current_password, $staff->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Model's 'hashed' cast will hash the new password automatically
        $staff->update(['password' => $request->password]);

        return redirect()->route('staff.portal')->with('success', 'Password changed successfully.');
    }
}
