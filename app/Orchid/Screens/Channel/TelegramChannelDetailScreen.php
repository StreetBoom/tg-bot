<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Channel;

use App\Models\Channel;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Sight;

class TelegramChannelDetailScreen extends Screen
{
    /**
     * @var Channel
     */
    public $channel;

    /**
     * Query data.
     *
     * @param Channel $channel
     *
     * @return array
     */
    public function query(Channel $channel): array
    {
        $this->channel = $channel;

        return [
            'channel' => $channel,
        ];
    }

    /**
     * Name of the screen.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Детали канала';
    }

    public function layout(): array
    {
        return [
            Layout::legend('channel', [
                Sight::make('avatar', 'Изображение канала')
                    ->render(function (Channel $channel) {
                        // Убедитесь, что путь к аватару корректен и файл существует
                        if ($channel->avatar) {
                            return "<img src='" . asset($channel->avatar) . "' style='height: 100px; width: auto;'>";
                        }
                        return 'Нет изображения'; // Возвращаем этот текст, если изображение отсутствует
                    }),
                Sight::make('channel_id', 'ID канала'),
                Sight::make('title', 'Название канала'),
                Sight::make('username', 'Тег канала'),
                Sight::make('has_permissions', 'Наличие прав у бота'),
                Sight::make('created_at', 'Дата создания'),
                Sight::make('updated_at', 'Дата обновления'),
            ]),
        ];
    }
}
