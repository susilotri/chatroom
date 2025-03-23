<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatRoomController;

Route::get('/test', function () {
    return response()->json(['message' => 'API route works!']);
});

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::post('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::post('/auth/register', [AuthController::class, 'registerUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/chatroom/create', [ChatRoomController::class, 'create']);
    Route::post('/chatroom/invite/{chatRoomId}', [ChatRoomController::class, 'inviteUser']);
    Route::post('/chatroom/respond/{chatRoomId}', [ChatRoomController::class, 'respondToInvite']);
    Route::get('/chatroom/active', [ChatRoomController::class, 'listActiveChatRooms']);
    Route::get('/chatroom/my', [ChatRoomController::class, 'myChatRooms']);
    Route::delete('/chatroom/leave/{chatRoomId}', [ChatRoomController::class, 'leaveChatRoom']);
    Route::post('/chatroom/join/{chatRoomId}', [ChatRoomController::class, 'joinChatRoom']);
    Route::get('/chatroom/{chatRoomId}', [ChatRoomController::class, 'getChatRoomDetails']);


});
