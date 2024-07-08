<?php

namespace App\Services\User;

use App\DTO\User\TelegramUserDTO;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramAuthService
{
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
        $avatar = null;

        // Если пользователь не существует или у него нет аватара, скачиваем аватар
        if (!$user || !$user->avatar) {
            $avatar = isset($data['photo']) ? $this->downloadUserProfilePhoto($bot->token, $data['photo']) : null;
        }

        // Создаем DTO из данных
        $telegramUserDTO = new TelegramUserDTO([
            'name' => $data['first_name'],
            'telegram_id' => $data['id'],
            'username' => $data['username'] ?? null,
            'avatar' => $avatar ?? $user->avatar ?? null, // Используем существующий аватар, если он есть
        ]);

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

        return true;
    }

    private function downloadUserProfilePhoto(string $botToken, array $photo): ?string
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

            // Скачиваем файл
            $fileContent = Http::get($fileUrl)->body();

            // Генерируем уникальное имя файла и сохраняем его в публичную директорию
            $fileName = 'avatars/' . uniqid() . '.jpg';
            $publicPath = public_path($fileName);
            file_put_contents($publicPath, $fileContent);

            Log::info("Файл скачан и сохранен как: {$publicPath}");

            return url($fileName);
        } else {
            Log::error("Ошибка при получении информации о файле: " . $fileInfo['description']);
            return null;
        }
    }
}
