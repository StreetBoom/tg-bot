<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\TelegramUser;

use App\Models\TelegramUser;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TelegramUserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [

            TD::make('user.name', __('Имя'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (TelegramUser $user) {
                    return Link::make((string)$user->name)
                        ->route('platform.tg-users.detail', $user->id);
                }),

            TD::make('user.telegram_id', __('Telegram ID'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (TelegramUser $user) {
                    return (string)$user->telegram_id;
//                        ->route('platform.tg-users.edit', $user->id);
                }),

            TD::make('user.username', __('Username'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (TelegramUser $user) {
                    return (string)$user->username;
                }),
//                        ->route('platform.tg-users.edit', $user->id);


            TD::make('channels', __('Каналы'))
                ->render(function (TelegramUser $user) {
                    return $user->channels->map(function ($user) {
                        return '<a href="' . route('platform.channels.detail', $user->id) . '">' . e($user->title) . '</a>';
                    })->implode(', ');
                }),


            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (TelegramUser $user) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

//                        Link::make(__('Редактировать'))
//                            ->route('platform.tg-users.edit', $user->id)
//                            ->icon('bs.pencil'),

                        Button::make(__('Удалить'))
                            ->icon('bs.trash3')
                            ->confirm(__('Вы уверены, что хотите удалить пользователя безвозвратно?'))
                            ->method('remove', [
                                'id' => $user->id,
                            ]),
                    ])),

        ];
    }
}
