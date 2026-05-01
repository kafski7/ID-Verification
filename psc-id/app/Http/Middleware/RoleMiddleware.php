<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Restrict access to users whose role is in the allowed list.
     *
     * Usage in routes: ->middleware('role:SUPER_ADMIN,HR_ADMIN')
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || (count($roles) > 0 && ! in_array($user->role, $roles, true))) {
            abort(403, 'Unauthorised.');
        }

        return $next($request);
    }
}
