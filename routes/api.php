<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['web', 'auth'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/chat/messages/{receiver}', [\App\Http\Controllers\Api\ChatController::class, 'fetchMessages'])
        ->where('receiver', '[0-9]+')
        ->name('chat.fetchMessages');
    Route::post('/chat/send', [\App\Http\Controllers\Api\ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    Route::post('/chat/mark-as-read/{receiver}', [\App\Http\Controllers\Api\ChatController::class, 'markAsRead'])->name('chat.markAsRead');
    Route::get('/chat/sellers', [\App\Http\Controllers\Api\ChatController::class, 'listSellers'])->name('chat.listSellers');
    Route::get('/chat/users', [\App\Http\Controllers\Api\ChatController::class, 'listUsers'])->name('chat.listUsers');
    Route::get('/chat/users-for-seller', [\App\Http\Controllers\Api\ChatController::class, 'listUsersForSeller'])->name('chat.listUsersForSeller');
    Route::get('/chat/seller/{seller}', [\App\Http\Controllers\Api\ChatController::class, 'getSellerDetails'])->name('chat.getSellerDetails');
});
