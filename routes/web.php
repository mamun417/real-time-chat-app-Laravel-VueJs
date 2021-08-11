<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('users', [\App\Http\Controllers\ChatController::class, 'getUserList']);
Route::get('chats', [\App\Http\Controllers\ChatController::class, 'index']);
Route::get('users/{user_id}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages']);
Route::post('messages', [\App\Http\Controllers\ChatController::class, 'sendMessages']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
