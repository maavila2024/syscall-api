<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidPasswordResetTokenException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordByTokenController extends Controller
{
    /**
     * Reset password using token (no need for current password)
     */
    public function __invoke(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find token
        $resetToken = PasswordResetToken::query()
            ->where('token', $token)
            ->whereDate('created_at', '>=', now()->subHours(24)->toDateTimeString())
            ->first();

        if (!$resetToken) {
            throw new InvalidPasswordResetTokenException();
        }

        $user = User::find($resetToken->user_id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->must_change_password = false; // Não precisa mais alterar no próximo login
        $user->save();

        // Delete all reset tokens for this user
        $user->resetPasswordTokens()->delete();

        return response()->json([
            'message' => 'Senha redefinida com sucesso!'
        ], 200);
    }

    /**
     * Get user info by token (to show email in form)
     */
    public function show($token)
    {
        $resetToken = PasswordResetToken::query()
            ->where('token', $token)
            ->whereDate('created_at', '>=', now()->subHours(24)->toDateTimeString())
            ->first();

        if (!$resetToken) {
            return response()->json([
                'message' => 'Token inválido ou expirado'
            ], 404);
        }

        $user = User::find($resetToken->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], 404);
        }

        return response()->json([
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
        ], 200);
    }
}

