<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class InteractionCreated extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $title;
    public $interaction;
    public $task;

    public function __construct($title, $interaction, $task)
    {
        $this->title = $title;
        $this->interaction = $interaction;
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'interaction' => $this->interaction,
            'task' => $this->task,
        ]);
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'interaction' => $this->interaction,
            'task' => $this->task,
        ];
    }
}
