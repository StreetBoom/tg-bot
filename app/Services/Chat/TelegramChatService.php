<?php

namespace App\Services\Chat;

use App\DTO\User\TelegramUserDTO;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramChatService
{
    /**
     * Получение идентификатора чата по идентификатору пользователя Telegram.
     *
     * @param int $telegramUserId
     * @return string|null
     */
    public function getChatIdByTelegramUserId(int $telegramUserId): ?string
    {
        $user = User::where('telegram_id', $telegramUserId)->first();
        return $user ? $user->telegraphChat->chat_id : null;
    }
}
