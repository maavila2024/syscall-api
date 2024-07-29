<?php

namespace App\Events;

use App\Models\Interaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InteractionCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $interaction;

    public function __construct(Interaction $interaction)
    {
        $this->interaction = $interaction;
    }

    public function broadcastOn()
    {
        return new Channel('tasks.' . $this->interaction->task_id);
    }
}
