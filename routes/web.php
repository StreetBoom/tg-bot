<?php

use App\Http\Controllers\TelegramAuthController;
use App\Http\Controllers\TelegramController;
use App\Http\Middleware\TelegramAuth;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use DefStudio\Telegraph\Telegraph;


Route::get('/', function () {
    $user = Auth::user();
    $bot = TelegraphBot::first();
    $botName = $bot->name;

    return view('index', [
        'user' => $user ? array_merge($user->only(['name', 'avatar']), ['isAuthenticated' => true]) : ['isAuthenticated' => false],
        'botName' => $botName
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

Route::middleware('telegram_auth')->get('get-channels', [TelegramController::class, 'getChannelsByUserId'])->name('user.channels');

Route::post('/logout', [TelegramAuthController::class, 'logout'])->name('logout');

