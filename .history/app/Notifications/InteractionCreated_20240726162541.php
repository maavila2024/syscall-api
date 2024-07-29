<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class InteractionCreated extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $title;
    protected $taskCode;
    protected $comment;

    public function __construct($title, $taskCode, $comment)
    {
        $this->title = $title;
        $this->taskCode = $taskCode;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $notification  = [
            // 'data' => [
                'title' => $this->title,
                'task_code' => $this->taskCode,
                'interaction' => [
                    'comment' => $this->comment,
                ],
            // ]
        ];
        return new BroadcastMessage($notification);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'task_code' => $this->taskCode,
            'interaction' => [
                'comment' => $this->comment,
            ],
        ];
    }
}
