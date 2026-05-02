<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function show(Request $request, string $token)
    {
        return view('staff.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token'                 => ['required'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker('staff')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($staff, $password) {
                // The 'hashed' cast on the model handles hashing automatically
                $staff->forceFill([
                    'password'       => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($staff));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('staff.login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
