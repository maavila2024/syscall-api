<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Log;

class PasswordResetRequested extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $userEmail;
    protected $userName;
    protected $userId;

    /**
     * Create a new notification instance.
     */
    public function __construct($userEmail, $userName, $userId)
    {
        $this->userEmail = $userEmail;
        $this->userName = $userName;
        $this->userId = $userId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        Log::info('🔔 [NOTIFICATION] PasswordResetRequested - via() chamado', [
            'notifiable_id' => $notifiable->id,
            'notifiable_email' => $notifiable->email,
            'broadcast_driver' => config('broadcasting.default'),
        ]);

        return ['broadcast', 'database'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $notification = [
            'data' => [
                'title' => 'Solicitação de Reset de Senha',
                'message' => "O usuário {$this->userName} ({$this->userEmail}) solicitou reset de senha.",
                'type' => 'password_reset_request',
                'user_id' => $this->userId,
                'user_email' => $this->userEmail,
                'user_name' => $this->userName,
            ]
        ];

        Log::info('🔔 [NOTIFICATION] PasswordResetRequested - toBroadcast() chamado', [
            'notifiable_id' => $notifiable->id,
            'notifiable_email' => $notifiable->email,
            'channel' => $notifiable->receivesBroadcastNotificationsOn(),
            'notification_data' => $notification,
        ]);

        return new BroadcastMessage($notification);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Solicitação de Reset de Senha',
            'message' => "O usuário {$this->userName} ({$this->userEmail}) solicitou reset de senha.",
            'type' => 'password_reset_request',
            'user_id' => $this->userId,
            'user_email' => $this->userEmail,
            'user_name' => $this->userName,
        ];
    }
}

