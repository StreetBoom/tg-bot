<?php

namespace App\Services\Channel;

use App\Models\Channel;
use App\Models\User;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramChannelService
{
    public function getChannelsByUserId($user)
    {
        if(!$user) {
            return null;
        }
        return $user->channels()->get();
    }

    /**
     * Сохраняем данные подключеного канала
     *
     * @param array $data
     * @param TelegraphBot $bot
     * @return string
     */
    public function addChannel(array $data, TelegraphBot $bot): string
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

        // Проверка прав
        $hasPermissions = $this->hasPermissions($data['my_chat_member']['new_chat_member']);


        if (!$channel || !$channel->avatar) {
            $avatar = $this->downloadChannelAvatar($bot->token, $channelId);
        }
        $channel = Channel::updateOrCreate(
            ['channel_id' => $channelId],
            [
                'title' => $title,
                'username' => $username,
                'avatar' => $avatar ?? $channel->avatar ?? null,
                'has_permissions' => $hasPermissions,
            ]
        );

        // Логика связывания пользователя и канала
        $userId = $data['my_chat_member']['from']['id'];
        $user = User::where('telegram_id', $userId)->first();

        //Если пользователь существует, то связывает пользователя и канал
        if ($user) {
            $user->channels()->syncWithoutDetaching([$channel->id]);
        }

        Log::info("Бот добавлен в канал: {$channel->title}");
        // Возвращаем сообщение в зависимости от прав
        if ($hasPermissions) {
            return "Бот был добавлен на $channel->title и имеет все необходимые права!";
        } else {
            return "Пожалуйста, предоставьте боту следующие права на канале $channel->title: Публикация сообщений, Редактирование чужих публикаций, Удаление чужих публикаций, Публикация историй, Изменение чужих историй, Удаление чужих историй, Добавление участников.";
        }
    }

    /**
     * Сохраняем фото канала
     *
     * @param string $botToken
     * @param int $channelId
     * @return string|null
     */
    private function downloadChannelAvatar(string $botToken, int $channelId): ?string
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
            $fileName = "images/channelAvatars/{$channelId}.jpg";
            $publicPath = public_path($fileName);

            // Проверяем директорию
            $directory = public_path('images/channelAvatars');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Сохраняем файл
            file_put_contents($publicPath, $fileContent);

            Log::info("Файл скачан и сохранен как: {$publicPath}");

            return "/images/channelAvatars/{$channelId}.jpg";
        } else {
            Log::error("Ошибка при получении информации о файле: " . $fileInfo['description']);
            return null;
        }
    }


    /**
     * @param array $newChatMember
     * @return bool
     */
    private function hasPermissions(array $newChatMember): bool
    {
        $requiredPermissions = [
            'can_post_messages',
            'can_edit_messages',
            'can_delete_messages',
            'can_post_stories',
            'can_edit_stories',
            'can_delete_stories',
            'can_invite_users'
        ];

        foreach ($requiredPermissions as $permission) {
            if (empty($newChatMember[$permission])) {
                return false;
            }
        }

        return true;
    }

}
