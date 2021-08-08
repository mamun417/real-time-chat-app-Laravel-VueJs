<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    broadcast(new \App\Events\WebSocketDemoEvent('some data'));

    return view('welcome');
});

Route::get('chats', [\App\Http\Controllers\ChatController::class, 'index']);
Route::get('messages', [\App\Http\Controllers\ChatController::class, 'fetchMessages']);
Route::post('messages', [\App\Http\Controllers\ChatController::class, 'sendMessages']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
