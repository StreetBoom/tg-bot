<?php

namespace App\Services\User;

use App\DTO\User\TelegramUserDTO;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramUserService
{
    /**
     * Получение пользователя по идентификатору чата.
     *
     * @param int $chatId
     * @return User|null
     */
    public function getUserByChatId(int $chatId): ?User
    {
        // Предполагается, что идентификатор чата хранится в поле telegraph_chat_id в таблице users
        return User::where('telegraph_chat_id', $chatId)->first();
    }
}
