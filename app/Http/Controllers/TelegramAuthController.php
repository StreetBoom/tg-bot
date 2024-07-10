<?php

namespace App\Http\Controllers;

use App\Services\User\TelegramAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramAuthController extends Controller
{
    public function __construct(TelegramAuthService $telegramAuthService)
    {
        $this->telegramAuthService = $telegramAuthService;
    }

    public function handleTelegramCallback(Request $request)
    {
        $chatId = $request->query('chat_id');
        $result = $this->telegramAuthService->handleTelegramCallback($chatId);

        if (!$result) {
            return redirect('/')->withErrors(['msg' => 'Invalid Telegram data']);
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
