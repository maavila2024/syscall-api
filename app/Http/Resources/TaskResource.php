<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'segment' => $this->segment,
            'description' => $this->description,
            'owner_id' => $this->owner_id,
            'responsible_id' => $this->responsible_id,
            'task_status_id' => $this->task_status_id,
            'system_screen' => $this->system_screen,
            'observation' => $this->observation,
            'priority_id' => $this->priority_id,
            'priority_justification' => $this->priority_justification,
            'review_justification' => $this->review_justification,
            'expected_date' => $this->expected_date,
            'finish_date' => $this->finish_date,
            // 'user' => $this->whenLoaded('user')
        ];
    }
}
