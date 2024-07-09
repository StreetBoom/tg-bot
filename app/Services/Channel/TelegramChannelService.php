<?php

namespace App\Services\Channel;

use App\Models\Channel;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramChannelService
{

    public function getChannelInfo(array $data): ?array
    {
        $bot = TelegraphBot::first();
        $channelId = $data['my_chat_member']['chat']['id'];

        // Выполняем запрос к API Telegram для получения данных о канале
        $response = Http::post("https://api.telegram.org/bot{$bot->token}/getChat", [
            'chat_id' => $channelId,
        ]);

        if ($response->successful()) {
            $chatData = $response->json();
            if (isset($chatData['result'])) {
                Log::info("Данные о канале получены: " . json_encode($chatData['result'], JSON_UNESCAPED_UNICODE));
                return $chatData['result'];
            } else {
                Log::warning("Не удалось получить данные о канале. Ответ API: " . json_encode($chatData, JSON_UNESCAPED_UNICODE));
                return null;
            }
        } else {
            Log::error("Ошибка при выполнении запроса к API Telegram: " . $response->body());
            return null;
        }
    }

    public function handleBotAddedToChannel(array $data, TelegraphBot $bot): void
    {
        $data = json_decode(json_encode($data), true);
        Log::info('все данные', $data);
        // Извлекаем данные канала
        $channelData = $data['my_chat_member']['chat'];
        $title = $channelData['title'];
        $channelId = $channelData['id'];
        $username = $channelData['username'] ?? null;

        // Проверяем, существует ли канал
        $channel = Channel::where('channel_id', $channelId)->first();

        $avatar = null;
        if (!$channel || !$channel->avatar) {
            $avatar = $this->downloadChannelPhoto($bot->token, $channelId);
        }
        $channel = Channel::updateOrCreate(
            ['channel_id' => $channelId],
            ['title' => $title, 'username' => $username, 'avatar' => $avatar ?? $channel->avatar ?? null]
        );

        // Логика связывания пользователя и канала
        $userId = $data['my_chat_member']['from']['id'];
        $user = User::where('telegram_id', $userId)->first();

        //Если пользователь существует, то связывает пользователя и канал
        if ($user) {
            $user->channels()->syncWithoutDetaching([$channel->id]);
        }

        Log::info("Бот добавлен в канал: {$channel->title}");
    }

    private function downloadChannelPhoto(string $botToken, int $channelId): ?string
    {
        // Получаем информацию о фото канала
        $response = Http::post("https://api.telegram.org/bot{$botToken}/getChat", [
            'chat_id' => $channelId,
        ]);

        $chatData = $response->json()['result'] ?? null;
        if (!$chatData || !isset($chatData['photo'])) {
            return null;
        }

        $photo = $chatData['photo'];
        $fileId = $photo['big_file_id'] ?? $photo['small_file_id']; // Используем big_file_id для лучшего качества, fallback на small_file_id

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
            $fileName = 'channel/' . uniqid() . '.jpg';
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
