<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    broadcast(new \App\Events\WebSocketDemoEvent('some data'));

    return view('welcome');
});

Route::get('users', [\App\Http\Controllers\ChatController::class, 'getUserList']);
Route::get('chats', [\App\Http\Controllers\ChatController::class, 'index']);
Route::get('messages', [\App\Http\Controllers\ChatController::class, 'fetchMessages']);
Route::post('messages', [\App\Http\Controllers\ChatController::class, 'sendMessages']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
