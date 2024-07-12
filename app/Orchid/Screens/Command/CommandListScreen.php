<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Command;

use App\Models\Command;
use App\Orchid\Layouts\Command\CommandListLayout;


use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CommandListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'commands' => Command::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Список команд';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Добавить'))
                ->icon('bs.plus-circle')
                ->href(route('platform.commands.create')),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            CommandListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        Command::findOrFail($request->get('id'))->delete();

        Toast::info(__('Команда удалена'));
    }
}
