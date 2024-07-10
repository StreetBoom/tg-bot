<?php

namespace App\Telegram;

use AllowDynamicProperties;
use App\Models\StaticCommand;
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


#[AllowDynamicProperties] class Handler extends WebhookHandler
{
    public function __construct(
        TelegramChannelService $telegramChannelService,
        TelegramUserService    $telegramUserService,
        TelegramChatService    $telegramChatService,
        Telegraph              $telegraph)

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
        $this->reply("Добро пожаловать! Перед использованием нашего бота нужно авторизоваться, для этого пройдите по кнопке ниже:\n[Авторизация]($registrationUrl)");
    }


    /**
     * @param Request $request
     * @param TelegraphBot $bot
     * @return void
     */
    public function handle(Request $request, TelegraphBot $bot): void
    {
        $data = $request->all();
        if (isset($data['message']['text'])){
            $command = StaticCommand::where('command', $data['message']['text'])->first();
        }
        if (isset($data['my_chat_member']) && $data['my_chat_member']['chat']['type'] === 'channel') {
            $this->handleChannelMember($data, $bot);

        } elseif (isset($command)) {
            $staticCommand = StaticCommand::where('command', $data['message']['text'])->first();

            // Отправляем сообщение, если команда найдена
            $this->telegramChatService->sendMessage($data['message']['chat']['id'], $staticCommand->message);
        } else {
            parent::handle($request, $bot);
        }
    }

    /**
     * @param array $data
     * @param TelegraphBot $bot
     * @return void
     */
    protected
    function handleChannelMember(array $data, TelegraphBot $bot): void
    {
        $chatUserId = $data['my_chat_member']['from']['id'];

        //При добавление бота на канал
        if (isset($data['my_chat_member']['new_chat_member']['status']) && $data['my_chat_member']['new_chat_member']['status'] === 'administrator') {
            $message = $this->telegramChannelService->addChannel($data, $bot);

            $this->telegramChatService->sendMessage($chatUserId, $message);

        }
        //При удаление бота с канала

        if (isset($data['my_chat_member']['new_chat_member']['status']) && $data['my_chat_member']['new_chat_member']['status'] === 'left') {
            Log::info('Обнаружен статус выхода.');
            $channelName = $data['my_chat_member']['chat']['title'];
            $chatUserId = $this->telegramChatService->getChatIdByChannelId($data['my_chat_member']['chat']['id']);
            if ($chatUserId) {
                $this->telegramChatService->sendMessage($chatUserId, 'Бот был удален с канала!');
            } else {
                Log::warning("Не удалось найти chat_id");
            }
        }
    }
}
