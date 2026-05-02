<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyController extends Controller
{
    public function edit()
    {
        return view('staff.privacy.edit', ['staff' => Auth::guard('staff')->user()]);
    }

    public function update(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        $staff->update([
            'privacy_settings' => [
                'hide_staff_id'       => !$request->boolean('show_staff_id'),
                'hide_grade'          => !$request->boolean('show_grade'),
                'hide_phone'          => !$request->boolean('show_phone'),
                'hide_email'          => !$request->boolean('show_email'),
                'hide_other_contacts' => !$request->boolean('show_other_contacts'),
            ],
        ]);

        return back()->with('success', 'Privacy settings saved.');
    }
}
