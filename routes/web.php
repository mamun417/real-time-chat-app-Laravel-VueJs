<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// need auth middleware check
Route::get('users', [\App\Http\Controllers\UserController::class, 'index']);
Route::get('users/{user_id}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages']);
Route::post('send-message', [\App\Http\Controllers\ChatController::class, 'sendMessage']);
Route::post('remove-message/{message_id}', [\App\Http\Controllers\ChatController::class, 'removeMessage']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/admins/change-password/before-login/{admin_id}', [App\Http\Controllers\AuthController::class, 'changeAdminPasswordForBeforeLogin'])->name('before-login-change-user-password');

Route::post('conversations', [\App\Http\Controllers\ConversationController::class, 'store']);
Route::get('conversations/{id}/messages', [\App\Http\Controllers\ConversationController::class, 'getMessages']);

Route::get('{path}', [\App\Http\Controllers\HomeController::class, 'index'])
    ->where('path', '[A-Za-z0-9]+');
