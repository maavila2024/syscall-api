<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes([
            'prefix' => 'api',
            'middleware' => 'auth:sanctum'
        ]);

        Log::info('🔔 [BROADCAST] BroadcastServiceProvider inicializado', [
            'default_driver' => config('broadcasting.default'),
            'pusher_configured' => !empty(config('broadcasting.connections.pusher.key')),
        ]);

        require base_path('routes/channels.php');
    }
}
