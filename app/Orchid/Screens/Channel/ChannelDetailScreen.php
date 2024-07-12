<?php

namespace App\Orchid\Screens\Channel;

use App\Models\Channel;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class ChannelDetailScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
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

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('channel', [
                Sight::make('avatar', 'Аватарка')
                    ->render(function ($channel) {
                        return '<img src="' . e($channel->avatar) . '" alt="Avatar" style="max-width: 150px; max-height: 150px;">';
                    }),
                Sight::make('title', 'Название'),
                Sight::make('channel_id', 'Telegram ID'),
                Sight::make('username', 'Username'),
                Sight::make('has_permissions', 'Права бота на канале'),
                Sight::make('created_at', 'Дата создания'),
                Sight::make('updated_at', 'Дата обновления'),
            ]),
        ];
    }
}
