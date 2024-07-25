<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    use InteractsWithQueue;  // adicionei após assistir vídeo sobre envio de e-mails.

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;
        // dd($user->email);
        Mail::to($user->email)
        ->send(new WelcomeMail($user));
    }
}
