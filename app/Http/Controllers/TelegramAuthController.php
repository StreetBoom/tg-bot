<?php

namespace App\Http\Controllers;

use App\Services\TelegramAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Support\Facades\Log;

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

        return redirect()->route('home');    }

}
