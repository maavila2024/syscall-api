<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidAuthenticationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * The function takes a validated login request, attempts authentication, regenerates the session,
     * and returns a UserResource representing the authenticated user.
     *
     * @param LoginRequest request The `request` parameter in the `__invoke` method is an instance of
     * `LoginRequest`. It is a custom request class that likely contains validation rules and logic for
     * validating login input data. The `validated()` method is used to retrieve validated input data
     * from the request.
     *
     * @return UserResource A `UserResource` object is being returned.
     */
    public function __invoke(LoginRequest $request): UserResource
    {
        $input = $request->validated();
        if (!auth()->attempt($input)) {
            throw new InvalidAuthenticationException();
        }
        request()->session()->regenerate();
        return new UserResource(auth()->user());
    }
}
