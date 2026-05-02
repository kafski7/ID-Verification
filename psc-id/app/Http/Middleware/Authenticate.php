<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('staff/*') || $request->routeIs('staff.*')) {
            return route('staff.login');
        }

        return route('login');
    }
}
