<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InteractionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
            'user_email' => $this->user->email,
            'interaction_files' => $this->interactionFiles->map(function ($file) {
                return [
                    'name' => $file->name,
                    'path' => $file->path,
                ];
            }),
        ];
    }
}
