<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function show() {
        $user = auth()->user()->load(['teams', 'tasks', 'notifications']); // Carrega as notificações
        return new UserResource($user);
    }
}
