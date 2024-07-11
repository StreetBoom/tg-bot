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
     * @param string $chatId
     * @param string $message
     * @param string|null $image
     * @param array|null $dataInline
     * @return void
     */
    public function sendMessage(string $chatId, string $message, ?string $image = null, ?array $dataInline = null): void
    {
        try {
            Log::info("Attempting to send message to chat_id: $chatId with message: $message");

            $telegraph = $this->telegraph->chat($chatId)->message($message);

            //Добавляем фото
            if (isset($image)) {
                Log::info("Adding photo: $image");
                $telegraph->photo(public_path($image), 'file');
            }
            //Добавляем кнопки
            if (isset($dataInline)) {
                Log::info("Adding inline buttons");
                $telegraph->withData('reply_markup', json_encode([
                    'inline_keyboard' => $dataInline,
                ]));
            }

            $response = $telegraph->send();

            Log::info("Message sent successfully: " . json_encode($response));
        } catch (\Exception $e) {
            Log::error("Failed to send message: " . $e->getMessage());
        }
    }

}
