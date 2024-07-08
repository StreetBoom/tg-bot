<?php

namespace App\Telegram;

use DefStudio\Telegraph\DTO\Chat;
use DefStudio\Telegraph\DTO\TelegramUpdate;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use DefStudio\Telegraph\DTO\Message;
use Illuminate\Support\Stringable;

class Handler extends WebhookHandler
{
    public function hello()
    {
        $this->reply('Привет! Я самый крутой бот!');
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->reply('Неизвестная команда');
    }

    public function start()
    {
        $chatId = $this->chat->chat_id;

        $registrationUrl = route('telegram.auth.callback', ['chat_id' => $chatId]);
        $this->reply("Добро пожаловать! Для авторизации пройдите по кнопке ниже:\n[Авторизация]($registrationUrl)");
    }


    public function handle(Request $request, TelegraphBot $bot): void
    {
        Log::info('Webhook data received: ' . json_encode($request->all()));

        $data = $request->all();
        $telegramUpdate = TelegramUpdate::fromArray($data);

        Log::info('Webhook data received: ' . json_encode($telegramUpdate));

        if (isset($data['my_chat_member'])) {
            $this->handleMyChatMember($data);
        } else {
            parent::handle($request, $bot);
        }
    }

    protected function handleMyChatMember(array $data): void
    {
        Log::info('handleMyChatMember called');
        Log::info('Data: ' . json_encode($data));

        $chat = $data['my_chat_member']['chat'];
        $newChatMember = $data['my_chat_member']['new_chat_member'];

        // Проверка, что это событие добавления бота в канал
        if ($newChatMember['status'] === 'administrator' && $newChatMember['user']['is_bot']) {
            $botId = $newChatMember['user']['id'];
            Log::info("Бот с ID {$botId} добавлен в канал {$chat['id']} с правами администратора");

            // Ваша логика при добавлении бота в канал
        }
    }

}
