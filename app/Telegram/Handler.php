<?php

namespace App\Telegram;

use App\Services\Channel\TelegramChannelService;
use App\Services\Chat\TelegramChatService;
use App\Services\User\TelegramUserService;
use DefStudio\Telegraph\DTO\Chat;
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
    public function __construct(
                                TelegramChannelService $telegramChannelService,
                                TelegramUserService $telegramUserService,
                                TelegramChatService $telegramChatService,
                                Telegraph $telegraph)

    {
        $this->telegramChannelService = $telegramChannelService;
        $this->telegramUserService = $telegramUserService;
        $this->telegramChatService = $telegramChatService;
        $this->telegraph = $telegraph;

    }

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
        $data = $request->all();

        if (isset($data['my_chat_member']) && $data['my_chat_member']['chat']['type'] === 'channel') {
            $this->handleChannelMember($data, $bot);
        } else {
            parent::handle($request, $bot);
        }
    }

    protected function handleChannelMember(array $data, TelegraphBot $bot): void
    {
        $chatId = $data['my_chat_member']['from']['id'];
        if (isset($data['my_chat_member']['new_chat_member']['status']) && $data['my_chat_member']['new_chat_member']['status'] === 'administrator') {
            $this->telegramChannelService->handleBotAddedToChannel($data, $bot);
//            $this->sendMessageToUser($chatId, 'Бот был добавлен на канал!');
        }

        if (isset($data['my_chat_member']['new_chat_member']['status']) && $data['my_chat_member']['new_chat_member']['status'] === 'left') {
            Log::info('Обнаружен статус выхода.');
            $channelName = $data['my_chat_member']['chat']['title'];
            $chatId = $this->telegramChatService->getChatIdByTelegramUserId($data['my_chat_member']['from']['id']);
            $this->sendMessageToUser($chatId, 'Бот был удален с канала!');
        }
    }

    protected function sendMessageToUser(string $chatId, string $message): void
    {
        $this->telegraph->chat($chatId)
            ->message($message)
            ->send();
    }
}
