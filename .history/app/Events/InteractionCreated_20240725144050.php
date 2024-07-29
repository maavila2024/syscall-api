<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class InteractionCreated extends Notification
{
    use Queueable;

    protected $interaction;

    public function __construct($interaction)
    {
        $this->interaction = $interaction;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'interaction' => $this->interaction,
            'task' => $this->interaction->task,
        ]);
    }
}
