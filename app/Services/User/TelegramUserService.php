<?php

namespace App\Services\User;

use App\Models\TelegramUser;

class TelegramUserService
{
    /**
     * Получение пользователя по идентификатору чата.
     *
     * @param int $chatId
     * @return TelegramUser|null
     */
    public function getUserByChatId(int $chatId): ?TelegramUser
    {
        // Предполагается, что идентификатор чата хранится в поле telegraph_chat_id в таблице users
        return TelegramUser::where('telegraph_chat_id', $chatId)->first();
    }
}
