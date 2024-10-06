<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        Log::info('CheckSession middleware acionado.');

        if (!Auth::check()) {
            // Adiciona um log e testa se a sessão expirou
            Log::warning('Sessão expirada. Retornando 419.');

            return response()->json(['message' => 'Session expired (via middleware)'], 419);
        }

        // Aqui você pode adicionar um callback para debug ou teste, se necessário
        return $next($request, function($response) {
            Log::info('Callback após o middleware foi acionado.');
            return $response;
        });
    }
}
