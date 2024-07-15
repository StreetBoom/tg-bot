<?php

declare(strict_types=1);

namespace App\Orchid\Screens\TelegramUser;

use App\Models\TelegramUser;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Sight;

class TelegramUserDetailScreen extends Screen
{
    /**
     * @var TelegramUser
     */
    public $user;

    /**
     * Query data.
     *
     * @param TelegramUser $user
     *
     * @return array
     */
    public function query(TelegramUser $user): array
    {
        $this->user = $user;

        return [
            'user' => $user,
        ];
    }

    /**
     * Name of the screen.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Детали пользователя';
    }

    public function layout(): array
    {
        return [
            Layout::legend('user', [
                Sight::make('avatar', 'Изображение пользователя')
                    ->render(function (TelegramUser $user) {
                        // Убедитесь, что путь к аватару корректен и файл существует
                        if ($user->avatar) {
                            return "<img src='" . asset($user->avatar) . "' style='height: 100px; width: auto;'>";
                        }
                        return 'Нет изображения'; // Возвращаем этот текст, если изображение отсутствует
                    }),
                Sight::make('name', 'Имя'),
                Sight::make('telegram_id', 'Telegram ID'),
                Sight::make('username', 'Username'),
                Sight::make('created_at', 'Дата создания'),
                Sight::make('updated_at', 'Дата обновления'),
            ]),
        ];
    }
}
