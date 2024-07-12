<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\TelegramUser;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class TelegramUserEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->title('Имя')
                ->placeholder('Введите имя')
                ->required(),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder('Введите email'),

            CheckBox::make('user.email_verified_at')
                ->title('Статус подтверждения email')
                ->sendTrueOrFalse()
                ->help('Отметьте, если email пользователя был подтвержден.')
                ->value(isset($user->email_verified_at)),

            Input::make('user.telegram_id')
                ->type('telegram_id')
                ->required()
                ->title(__('Telegram ID'))
                ->placeholder('Введите telegram id'),

            Input::make('user.username')
                ->type('username')
                ->required()
                ->title(__('username'))
                ->placeholder('Введите username'),
        ];
    }
}
