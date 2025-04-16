<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function show()
    {
        $user = auth()->user()
            ->load(['teams', 'tasks', 'notifications' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }]); // Carrega as notificações ordenadas
        return new UserResource($user);
    }


    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'default_pagination' => 'sometimes|integer|in:5,10,15',
        ]);

        $user->update($validated);

        return new UserResource($user->fresh()->load(['teams', 'tasks', 'notifications']));
    }

}
