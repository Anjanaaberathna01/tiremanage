<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // If user is logged in and must change password
        if ($user && $user->must_change_password) {

            // Allow access to the password change routes
            if ($request->routeIs('driver.password.form') || $request->routeIs('driver.password.update')) {
                return $next($request);
            }

            // Otherwise, redirect to the password change page
            return redirect()->route('driver.password.form');
        }

        return $next($request);
    }
}
