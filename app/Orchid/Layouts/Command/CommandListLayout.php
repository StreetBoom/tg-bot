<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Command;

use App\Models\Command;
use App\Models\StaticCommand;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CommandListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'commands';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('command.id', 'ID')
                ->sort()
                ->cantHide()
                ->render(function (StaticCommand $command) {
                    return Link::make((string)$command->id)
                        ->route('platform.commands.edit', $command->id);
                }),

            TD::make('command.command', __('Имя'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (StaticCommand $command) {
                    return Link::make((string)$command->command)
                        ->route('platform.commands.edit', $command->id);
                }),

            TD::make('command.image', __('Изображение'))
                ->cantHide()
                ->render(function (StaticCommand $command) {
                    return Link::make((string)$command->image)
                        ->route('platform.commands.edit', $command->id);
                }),

            TD::make('command.name', __('Название команды'))
                ->sort()
                ->cantHide()
                ->render(function (StaticCommand $command) {
                    return Link::make((string)$command->name)
                        ->route('platform.commands.edit', $command->id);
                }),

            TD::make('command.massage', __('Ответ'))
                ->sort()
                ->cantHide()
                ->render(function (StaticCommand $command) {
                    return Link::make((string)$command->message)
                        ->route('platform.commands.edit', $command->id);
                }),

            TD::make('command.status', __('Статус'))
                ->sort()
                ->cantHide()
                ->render(function (StaticCommand $command) {
                    $statusText = $command->status ? 'Активна' : 'Неактивна';
                    return Link::make($statusText)
                        ->route('platform.commands.edit', $command->id);
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (StaticCommand $command) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Редактировать'))
                            ->route('platform.commands.edit', $command->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Удалить'))
                            ->icon('bs.trash3')
                            ->confirm(__('Вы уверены, что хотите удалить команду безвозвратно?'))
                            ->method('remove', [
                                'id' => $command->id,
                            ]),
                    ])),

        ];
    }
}
