<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // If the request is for a client route, redirect to home page
            if ($request->is('client/*') || $request->is('client')) {
                return url('/');
            }
            
            // For admin routes, redirect to admin login
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            
            // For agent routes, redirect to agent login
            if ($request->is('agent/*') || $request->is('agent')) {
                return route('agent.login');
            }
            
            // Default fallback to home
            return url('/');
        }

        return null;
    }
}
