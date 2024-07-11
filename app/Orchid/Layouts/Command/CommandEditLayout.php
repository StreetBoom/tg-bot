<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Command;

use Orchid\Screen\Field;
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
            Input::make('command.name')
                ->title('Имя команды')
                ->placeholder('Enter the command name')
                ->required(),

            TextArea::make('command.response')
                ->title('Ответ')
                ->placeholder('Enter the response for the command')
                ->rows(3)
                ->maxlength(500)
                ->required(),
        ];
    }
}
