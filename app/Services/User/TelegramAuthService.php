<?php

namespace App\Services\User;

use App\DTO\User\TelegramUserDTO;
use App\Models\User;
use App\Services\Chat\TelegramChatService;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramAuthService
{

    /**
     * @param Telegraph $telegraph
     * @param TelegramChatService $telegramChatService
     */
    public function __construct(Telegraph           $telegraph,
                                TelegramChatService $telegramChatService)
    {
        $this->telegramChatService = $telegramChatService;
        $this->telegraph = $telegraph;
    }


    /**
     * Метод получает данные пользователя, связывает с чатом и сохраняет в базу
     *
     * @param int $chatId
     * @return bool
     */
    public function handleTelegramCallback(int $chatId): bool
    {
        $bot = TelegraphBot::first();

        // Получаем данные пользователя через API Telegram
        $response = Http::post("https://api.telegram.org/bot{$bot->token}/getChat", [
            'chat_id' => $chatId,
        ]);
        $data = $response->json()['result'];

        // Валидация данных Telegram
        if (!$data) {
            return false;
        }

        // Проверяем, существует ли пользователь в базе данных
        $user = User::where('telegram_id', $data['id'])->first();
        $isNewUser = false;
        $avatar = null;

        // Если пользователь не существует или у него нет аватара, скачиваем аватар
        if (!$user || !$user->avatar) {
            $avatar = isset($data['photo']) ? $this->getUserProfilePhotoUrl($bot->token, $data['photo']) : null;
        }

        // Создаем DTO из данных
        $telegramUserDTO = new TelegramUserDTO([
            'name' => $data['first_name'],
            'telegram_id' => $data['id'],
            'username' => $data['username'] ?? null,
            'avatar' => $avatar ?? $user->avatar ?? null, // Используем существующий аватар, если он есть
        ]);
        // Логика авторизации
        if (!$user) {
            $isNewUser = true;
        }

        // Логика авторизации
        $user = User::updateOrCreate(
            ['telegram_id' => $telegramUserDTO->telegram_id],
            [
                'username' => $telegramUserDTO->username,
                'name' => $telegramUserDTO->name,
                'avatar' => $telegramUserDTO->avatar,
            ]
        );

        // Связываем пользователя с чатом
        $chat = TelegraphChat::where('chat_id', $chatId)->first();
        if ($chat) {
            $user->telegraph_chat_id = $chat->id;
            $user->save();
        }

        Auth::login($user);

        // Отправляем сообщение пользователю об успешной регистрации, если это новый пользователь
        if ($isNewUser) {
            $this->telegramChatService->senMessage($chatId, 'Регистрация прошла успешно! Добро пожаловать! Теперь вы можете добавить меня к себе на канал! не забудьте выдать мне нужные права');
        }

        return true;
    }

    /**
     * Метод скачивает фото пользователя и сохраняет его
     *
     * @param string $botToken
     * @param array $photo
     * @return string|null
     */
    private function getUserProfilePhotoUrl(string $botToken, array $photo): ?string
    {
        $fileId = $photo['big_file_id']; // Используем big_file_id для лучшего качества

        // Получаем информацию о файле
        $response = Http::get("https://api.telegram.org/bot{$botToken}/getFile", [
            'file_id' => $fileId,
        ]);

        $fileInfo = $response->json();

        if ($fileInfo['ok']) {
            $filePath = $fileInfo['result']['file_path'];
            $fileUrl = "https://api.telegram.org/file/bot{$botToken}/{$filePath}";

            Log::info("Получен URL аватара: {$fileUrl}");

            return $fileUrl;
        } else {
            Log::error("Ошибка при получении информации о файле: " . $fileInfo['description']);
            return null;
        }
    }
}
