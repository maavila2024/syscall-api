<?php

namespace App\Http\Controllers\Auth;

use App\Events\ForgotPasswordRequested;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use App\Notifications\PasswordResetRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request)
    {
        $input = $request->validated();

        $user = User::query()
            ->whereEmail($input['email'])
            ->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        $token = $user->resetPasswordTokens()->create([
            'token' => Str::upper(Str::random(6)),
        ]);

        // Enviar notificação via Pusher para os usuários de suporte
        $supportEmails = [
            'marco.avila@grainproteintech.com',
            'joel.zatti@grainproteintech.com',
        ];

        Log::info('🔔 [FORGOT_PASSWORD] Iniciando envio de notificações', [
            'user_requesting' => $user->email,
            'user_id' => $user->id,
            'support_emails' => $supportEmails,
            'broadcast_driver' => config('broadcasting.default'),
        ]);

        foreach ($supportEmails as $supportEmail) {
            $supportUser = User::where('email', $supportEmail)->first();
            
            Log::info('🔔 [FORGOT_PASSWORD] Verificando usuário de suporte', [
                'support_email' => $supportEmail,
                'user_found' => $supportUser ? true : false,
                'user_id' => $supportUser?->id,
            ]);

            if ($supportUser) {
                try {
                    Log::info('🔔 [FORGOT_PASSWORD] Enviando notificação', [
                        'to_user_id' => $supportUser->id,
                        'to_user_email' => $supportUser->email,
                        'from_user_id' => $user->id,
                        'from_user_email' => $user->email,
                    ]);

                    $supportUser->notify(new PasswordResetRequested(
                        $user->email,
                        $user->first_name . ' ' . $user->last_name,
                        $user->id
                    ));

                    Log::info('🔔 [FORGOT_PASSWORD] Notificação enviada com sucesso', [
                        'to_user_id' => $supportUser->id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('🔔 [FORGOT_PASSWORD] Erro ao enviar notificação', [
                        'to_user_id' => $supportUser->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            } else {
                Log::warning('🔔 [FORGOT_PASSWORD] Usuário de suporte não encontrado', [
                    'support_email' => $supportEmail,
                ]);
            }
        }

        return response()->json([
            'message' => 'Sua solicitação de reset de senha foi enviada para o suporte. Você receberá um email interno em breve.'
        ], 200);
    }
}
