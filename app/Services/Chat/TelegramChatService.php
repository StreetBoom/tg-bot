<?php

namespace App\Services\Chat;

use App\DTO\User\TelegramUserDTO;
use App\Models\Channel;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramChatService
{
    private Telegraph $telegraph;

    /**
     * @param Telegraph $telegraph
     */
    public function __construct(Telegraph $telegraph)
    {
        $this->telegraph = $telegraph;
    }

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


    /**
     * Получение идентификатора чата пользователя по идентификатору канала Telegram.
     *
     * @param int $telegramChannelId
     * @return string|null
     */
    public function getChatIdByChannelId(int $telegramChannelId): ?string
    {
        $channel = Channel::where('channel_id', $telegramChannelId)->first();
        if ($channel) {
            $user = $channel->users()->first();
            if ($user) {
                return $user->telegraphChats()->first()->chat_id;
            }
        }
        return null;
    }

    /**
     * Отправляет сообщение пользователю в чат
     *
     * @param string $chatId
     * @param string $message
     * @return void
     */
    public function senMessage(string $chatId, string $message): void
    {
        $this->telegraph->chat($chatId)
            ->message($message)
            ->send();
    }
}
