<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\UserHasBeenTakenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        dd($request);
        $input = $request->validated();

        if (User::query()->whereEmail($input['email'])->exists()) {
            throw new UserHasBeenTakenException();
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::query()->create($input);

        return new UserResource($user);
    }
}
