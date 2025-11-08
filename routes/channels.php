<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notifications.{id}', function ($user, $id) {
    $authorized = (int)$user->id === (int)$id;
    
    Log::info('🔔 [BROADCAST] Autorizando canal notifications', [
        'user_id' => $user->id,
        'channel_id' => $id,
        'authorized' => $authorized,
    ]);
    
    return $authorized;
});
