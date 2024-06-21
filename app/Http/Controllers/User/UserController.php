<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::without('teams', 'tasks')->paginate(10));
    }

    public function store(UserStoreRequest $request)
    {
        return response()->json(User::create($request->validated()));
    }

    public function update(UserUpdateRequest $request, User $priority)
    {
        $priority->update($request->validated());
        return response()->json($priority);
    }

    public function destroy(User $priority)
    {
        $priority->delete();
        return response()->json('Procedimento Realizado');
    }

}
