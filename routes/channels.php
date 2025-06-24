<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\SellerChat;
use App\Models\User;

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

// Channel publik untuk notifikasi pendaftaran user baru
Broadcast::channel('public-notifications', function () {
    return true; // Channel publik, semua user bisa mendengarkan
});

Broadcast::channel('chat.{conversationId}', function (User $user, string $conversationId) {
    list($userId1, $userId2) = explode('-', $conversationId);
    return $user->id == $userId1 || $user->id == $userId2;
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('seller.{sellerId}', function ($user, $sellerId) {
    // Pastikan user adalah seller dan ID-nya cocok
    return $user->hasRole('seller') && (int) $user->id === (int) $sellerId;
});
