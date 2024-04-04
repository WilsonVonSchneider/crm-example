<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsActiveUser
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->isActive()) {
            
            Auth::logout();
            
            return redirect()->route('login')->with('status', 'Your account is inactive. Please contact support.');
        }

        return $next($request);
    }
}
