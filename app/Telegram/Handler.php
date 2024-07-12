<?php

namespace App\Telegram;

use AllowDynamicProperties;
use App\Models\StaticCommand;
use App\Services\Channel\TelegramChannelService;
use App\Services\Chat\TelegramChatService;
use App\Services\User\TelegramUserService;
use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\DTO\Chat as DTOChat;
use DefStudio\Telegraph\DTO\InlineQuery;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;
use Throwable;

#[AllowDynamicProperties]
class Handler extends WebhookHandler
{
    protected TelegramChannelService $telegramChannelService;
    protected TelegramUserService $telegramUserService;
    protected TelegramChatService $telegramChatService;
    protected Telegraph $telegraph;
    protected TelegraphBot $bot;
    protected Request $request;
    protected ?Message $message = null;
    protected ?CallbackQuery $callbackQuery = null;
    protected TelegraphChat $chat;

    public function __construct(
        TelegramChannelService $telegramChannelService,
        TelegramUserService    $telegramUserService,
        TelegramChatService    $telegramChatService,
        Telegraph              $telegraph
    )
    {
        $this->telegramChannelService = $telegramChannelService;
        $this->telegramUserService = $telegramUserService;
        $this->telegramChatService = $telegramChatService;
        $this->telegraph = $telegraph;
    }

    /**
     * Неизвестная команда
     *
     * @param string|Stringable $text
     * @return void
     */
    protected function handleUnknownCommand(string|Stringable $text): void
    {
        $this->reply('Неизвестная команда');
    }

    /**
     * Команда /start
     *
     * @return void
     */
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
     * @throws Throwable
     */
    public function handle(Request $request, TelegraphBot $bot): void
    {
        try {
            $this->bot = $bot;
            $this->request = $request;

            $data = $request->all();

            if ($this->request->has('message')) {
                $this->message = Message::fromArray($this->request->input('message'));
                $this->chat = $this->convertToTelegraphChat($this->message->chat());
                $this->handleMessage();
                return;
            }

            if ($this->request->has('edited_message')) {
                $this->message = Message::fromArray($this->request->input('edited_message'));
                $this->chat = $this->convertToTelegraphChat($this->message->chat());
                $this->handleMessage();
                return;
            }

            if ($this->request->has('channel_post')) {
                $this->message = Message::fromArray($this->request->input('channel_post'));
                $this->chat = $this->convertToTelegraphChat($this->message->chat());
                $this->handleMessage();
                return;
            }

            if ($this->request->has('callback_query')) {
                $this->callbackQuery = CallbackQuery::fromArray($this->request->input('callback_query'));
                $this->chat = $this->callbackQuery->message()?->chat() ? $this->convertToTelegraphChat($this->callbackQuery->message()->chat()) : null;
                $this->handleCallbackQuery();
            }

            if ($this->request->has('inline_query')) {
                $this->handleInlineQuery(InlineQuery::fromArray($this->request->input('inline_query')));
            }

            if (isset($data['my_chat_member']) && $data['my_chat_member']['chat']['type'] === 'channel') {
                $this->handleChannelMember($data, $bot);
            } elseif (isset($command)) {
                Log::info('Executing static command: ' . $command->command);
                $staticCommand = StaticCommand::where('command', $data['message']['text'])->first();
                $this->telegramChatService->sendMessage($data['message']['chat']['id'], $staticCommand->message);
            } else {
                $this->handleUnknownCommand($data['message']['text']);
            }
        } catch (Throwable $throwable) {
            $this->onFailure($throwable);
        }
    }

    /**
     * обработка всех команд
     *
     * @return void
     */
    protected function handleMessage(): void
    {
        if ($this->message->text() === '/start') {
            $this->start();
        } else {
            $command = StaticCommand::where('name', $this->message->text())->first();
            if ($command) {
                $this->telegramChatService->sendMessage($this->chat->chat_id, $command->message, $command->image);
            } else {
                $this->handleUnknownCommand($this->message->text());
            }
        }
    }

    /**
     * Взаимодейтсвие с кнопками
     *
     * @return void
     */
    protected function handleCallbackQuery(): void
    {
        // Логика обработки callback-запроса
        $this->reply('Callback query received');
    }

    /**
     * Взаимодействие с текстом
     *
     * @param InlineQuery $inlineQuery
     * @return void
     */
    protected function handleInlineQuery(InlineQuery $inlineQuery): void
    {
        // Логика обработки inline-запроса
        $this->reply('Inline query received');
    }

    /**
     * @param array $data
     * @param TelegraphBot $bot
     * @return void
     */
    protected function handleChannelMember(array $data, TelegraphBot $bot): void
    {
        $chatUserId = $data['my_chat_member']['from']['id'];

        if (isset($data['my_chat_member']['new_chat_member']['status']) && $data['my_chat_member']['new_chat_member']['status'] === 'administrator') {
            $message = $this->telegramChannelService->addChannel($data, $bot);
            $this->telegramChatService->sendMessage($chatUserId, $message);
        }

        if (isset($data['my_chat_member']['new_chat_member']['status']) && $data['my_chat_member']['new_chat_member']['status'] === 'left') {
            Log::info('Обнаружен статус выхода.');
            $channelName = $data['my_chat_member']['chat']['title'];
            $chatUserId = $this->telegramChatService->getChatIdByChannelId($data['my_chat_member']['chat']['id']);
            if ($chatUserId) {
                $this->telegramChatService->sendMessage($chatUserId, "Бот был удален с канала! $channelName");
            } else {
                Log::warning("Не удалось найти chat_id");
            }
        }
    }

    /**
     * @param DTOChat $dtoChat
     * @return TelegraphChat
     */
    private function convertToTelegraphChat(DTOChat $dtoChat): TelegraphChat
    {
        // Создаем новый экземпляр TelegraphChat и заполняем его данными из DTOChat
        $telegraphChat = new TelegraphChat();
        $telegraphChat->chat_id = $dtoChat->id();
        $telegraphChat->name = $dtoChat->title();

        return $telegraphChat;
    }
}
