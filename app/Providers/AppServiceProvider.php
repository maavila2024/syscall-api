<?php

namespace App\Providers;

use App\Events\ForgotPasswordRequested;
use App\Listeners\SendForgotPasswordToken;
use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Team::class, TeamPolicy::class);
        
        // Registrar listener para evento de forgot password
        Event::listen(
            ForgotPasswordRequested::class,
            SendForgotPasswordToken::class
        );
    }
}
