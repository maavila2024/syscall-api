<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $input = $request->validated();

        // $input['password'] = bcrypt($input['password']);
        $user = User::query()->create([
            ...$input,
            'password' => bcrypt($input['password'])
        ]);

        return new UserResource($user);
    }
}
