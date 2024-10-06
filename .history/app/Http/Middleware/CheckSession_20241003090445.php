<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            // Se o usuário não estiver autenticado, retornar um código 419 (Session Expired)
            return response()->json(['message' => 'Session expired'], 419);
        }

        return $next($request);
    }
}
