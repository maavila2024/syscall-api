<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * This PHP function converts object properties to an associative array.
     *
     * @param Request request The `toArray` function takes a `Request` object as a parameter. This
     * function is likely part of a class where it converts the object's properties (`id`, `name`,
     * `email`) into an associative array. The `Request` object is commonly used in web applications to
     * represent an HTTP request
     *
     * @return array An array containing the 'id', 'name', and 'email' properties of the object is
     * being returned.
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            // 'tasks' => $this->whenLoaded('tasks'),
            'notifications' => $this->whenLoaded('receivesBroadcastNotificationsOn'),
            // 'permissions' => $user->getAllPermissions()->pluck('name'),
        ];
    }
}
