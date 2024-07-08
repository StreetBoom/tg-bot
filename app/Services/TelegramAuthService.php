<?php

namespace App\Services;

use App\DTO\User\TelegramUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DefStudio\Telegraph\Models\TelegraphBot;

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

        // Создаем DTO из данных
        $telegramUserDTO = new TelegramUserDTO([
            'name' => $data['first_name'],
            'telegram_id' => $data['id'],
            'username' => $data['username'] ?? null,
            'avatar' => isset($data['photo']) ? $this->downloadUserProfilePhoto($bot->token, $data['photo']) : null,
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
