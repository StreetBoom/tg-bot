<?php

use App\Http\Controllers\TelegramAuthController;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use DefStudio\Telegraph\Telegraph;


Route::get('/', function () {
    $user = Auth::user();
    return view('index', [
        'user' => $user ? array_merge($user->only(['name', 'avatar']), ['isAuthenticated' => true]) : ['isAuthenticated' => false]
    ]);
})->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();
    return view('dashboard', [
        'user' => $user ? array_merge($user->only(['name', 'avatar']), ['isAuthenticated' => true]) : ['isAuthenticated' => false]
    ]);
})  ;

Route::get('/auth/telegram', [TelegramAuthController::class, 'redirectToTelegram'])->name('telegram.auth');
Route::get('/auth/telegram/callback', [TelegramAuthController::class, 'handleTelegramCallback'])->name('telegram.auth.callback');

