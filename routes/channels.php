<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan channel broadcast yang digunakan aplikasi Anda.
| Callback authorization akan menentukan apakah user berhak mendengarkan channel.
|
*/

// Channel broadcast untuk user (misal: notifikasi personal)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel privat untuk chat antara dua user
Broadcast::channel('chat.conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::find($conversationId);
    return $conversation && ($conversation->customer_id === $user->id || $conversation->seller_id === $user->id);
});
