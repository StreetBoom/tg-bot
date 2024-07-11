<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Command;

use App\Models\Command;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CommandListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'command';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Command $command) => Link::make($command->name)
                    ->route('platform.commands', $command->id)),

            TD::make('response', __('Response'))
                ->sort()
                ->cantHide(),

//            TD::make('created_at', __('Created'))
//                ->usingComponent(DateTimeSplit::class)
//                ->align(TD::ALIGN_RIGHT)
//                ->defaultHidden()
//                ->sort(),
//
//            TD::make('updated_at', __('Last edit'))
//                ->usingComponent(DateTimeSplit::class)
//                ->align(TD::ALIGN_RIGHT)
//                ->sort(),
        ];
    }
}
