<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Channel;

use App\Models\Channel;
use App\Models\TelegramUser;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ChannelListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'channels';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('channel.channel_id', __('ID канала'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Channel $channel) {
                    return Link::make((string)$channel->channel_id);
//                        ->route('platform.tg-users.edit', $user->id);
                }),

            TD::make('channel.title', __('Title'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Channel $channel) {
                    return Link::make((string)$channel->title);
//                        ->route('platform.tg-users.edit', $user->id);
                }),

            TD::make('channel.username', __('Username'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Channel $channel) {
                    return Link::make((string)$channel->username);
//                        ->route('platform.tg-users.edit', $user->id);
                }),

            TD::make('users', __('Пользователи'))
                ->render(function (Channel $channel) {
                    return $channel->users->pluck('username')->join(', ');
                }),

            TD::make('channel.has_permission', __('Наличие прав'))
                ->sort()
                ->cantHide()
                ->render(function (Channel $channel) {
                    $statusText = $channel->has_permission ? 'Имеются' : 'Не имеются';
//                    return Link::make($statusText)
//                        ->route('platform.tg-users.edit', $user->id);
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Channel $channel) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

//                        Link::make(__('Редактировать'))
//                            ->route('platform.tg-users.edit', $channel->id)
//                            ->icon('bs.pencil'),

                        Button::make(__('Удалить'))
                            ->icon('bs.trash3')
                            ->confirm(__('Вы уверены, что хотите удалить канал безвозвратно?'))
                            ->method('remove', [
                                'id' => $channel->id,
                            ]),
                    ])),

        ];
    }
}
