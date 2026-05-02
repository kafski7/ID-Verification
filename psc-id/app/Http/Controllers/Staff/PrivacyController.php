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
                'hide_staff_id'       => $request->boolean('hide_staff_id'),
                'hide_grade'          => $request->boolean('hide_grade'),
                'hide_phone'          => $request->boolean('hide_phone'),
                'hide_email'          => $request->boolean('hide_email'),
                'hide_other_contacts' => $request->boolean('hide_other_contacts'),
            ],
        ]);

        return back()->with('success', 'Privacy settings saved.');
    }
}
