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
            'id' => $this->id,
            'task_code' => $this->task_code,
            'name' => $this->name,
            'sequence' => $this->sequence,
            'segment' => $this->segment,
            'description' => $this->description,
            'owner_id' => $this->owner_id,
            'responsible_id' => $this->responsible_id,
            'task_status_id' => $this->task_status_id,
            'task_type' => $this->task_type,
            'system_screen' => $this->system_screen,
            'observation' => $this->observation,
            'priority_id' => $this->priority_id,
            'priority_justification' => $this->priority_justification,
            'complexity_id' => $this->complexity_id,
            'complexity_justification' => $this->complexity_justification,
            'review_justification' => $this->review_justification,
            'expected_date' => $this->expected_date,
            'finish_date' => $this->finish_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_owner' => new UserResource($this->whenLoaded('userOwner')),
            'user_responsible' => new UserResource($this->whenLoaded('userResponsible')),
            'task_status' => new TaskStatusResource($this->whenLoaded('taskStatus')),
            'priority' => new PriorityResource($this->whenLoaded('priority')),
            'complexity' => new ComplexityResource($this->whenLoaded('complexity'))
        ];
    }
}
