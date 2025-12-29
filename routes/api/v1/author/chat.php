<?php


use App\Http\Controllers\v1\Author\Chat\ChatController;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors','lang'])->prefix('v1/chat')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('send-user-to-admin', [ChatController::class, 'sendMessageToAdmin']);
        Route::get('get-admin-user-messages', [ChatController::class, 'getAdminUserMessage']);
        Route::post('send-to-public', [ChatController::class, 'sendToPublic']);
        Route::get('get-public-messages', [ChatController::class, 'getChatPublicMessage']);
        Route::post('send-to-user', [ChatController::class, 'sendMessageToUser']);
        Route::get('get-user-messages', [ChatController::class, 'getUserMessage']);
        Route::post('send-to-user-follower', [ChatController::class, 'sendToFollower']);
        Route::get('get-user-follower-messages', [ChatController::class, 'getFollowerMessage']);
        Route::get('get-user-conversations', [ChatController::class, 'getUserConversations']);
    });
});
