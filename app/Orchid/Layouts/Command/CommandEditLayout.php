<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Command;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class CommandEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('command.command')
                ->title('Команда')
                ->placeholder('Введите имя команды')
                ->required(),

            TextArea::make('command.message')
                ->title('Ответ')
                ->placeholder('Введите сообщение команды')
                ->rows(3)
                ->maxlength(500)
                ->required(),

            TextArea::make('command.name')
                ->title('Название команды')
                ->placeholder('Введите название команды, название будет выводиться в меню. (Оставьте пустым если не хотите чтобы команды выводилась в меню)')
                ->rows(3)
                ->maxlength(500),

            Input::make('command.image')
                ->type('file')
                ->title('Изображение')
                ->acceptedFiles('image/*')
                ->help('Загрузите изображение для команды'),

            CheckBox::make('command.status')
                ->title('Статус')
                ->sendTrueOrFalse(),
        ];
    }
}
