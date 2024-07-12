<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Command;

use App\Models\Command;
use App\Orchid\Layouts\Command\CommandEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CommandEditScreen extends Screen
{
    /**
     * @var Command
     */
    public $command;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Command $command): iterable
    {
        return [
            'command'  => $command,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->command->exists ? 'Изменение комманд' : 'Создание комманд';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return $this->command->exists ? [
            Button::make('Удалить')
                ->icon('trash')
                ->confirm('Вы точно хотите удалить эту комманду?')
                ->method('remove'),

            Button::make('Сохранить')
                ->icon('check')
                ->method('save'),
        ] :
            [
                Button::make('Создать')
                    ->icon('check')
                    ->method('create'),
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
            CommandEditLayout::class
        ];
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request)
    {
        $commandData = $request->command;
        Command::create($commandData);
        Toast::info('Команда была создан');
        return redirect()->route('platform.commands');
    }


    /**
     * @return RedirectResponse
     */
    public function save(Request $request, Command $command)
    {
        $request->validate([
            'command.name' => 'required',
            'command.response' => 'required',
            'command.status' => 'required',
        ]);

        $command->fill($request->get('command'));
        $command->save();

        Toast::info(__('Команда была сохранена'));

        return redirect()->route('platform.commands');
    }

    /**
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function remove(Command $command)
    {
        try {
            $command->delete();
            Toast::info(__('Команда была удалена'));
        } catch (\Exception $e) {
            Toast::error(__('Failed to remove command.'));
        }

        return redirect()->route('platform.commands');
    }
}
