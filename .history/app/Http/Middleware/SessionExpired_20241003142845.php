<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionExpired
{
    protected $timeout = 120; // Set timeout in seconds (2 minutes)

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (time() - session('lastActivityTime') > $this->timeout) {
                Auth::logout();
                return redirect('/login')->withErrors(['message' => 'Your session expired due to inactivity.']);
            }
            session(['lastActivityTime' => time()]);
        }

        return $next($request);
    }
}
