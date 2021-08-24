<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('users', [\App\Http\Controllers\ChatController::class, 'getUserList']);
Route::get('users/{user_id}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages']);
Route::post('send-message', [\App\Http\Controllers\ChatController::class, 'sendMessage']);
Route::post('remove-message/{message_id}', [\App\Http\Controllers\ChatController::class, 'removeMessage']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/admins/change-password/before-login/{admin_id}', [App\Http\Controllers\AuthController::class, 'changeAdminPasswordForBeforeLogin'])->name('before-login-change-user-password');
