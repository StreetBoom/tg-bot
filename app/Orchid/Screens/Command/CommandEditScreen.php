<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Command;

use App\Models\Command;
use App\Orchid\Layouts\Command\CommandEditLayout;
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
        return 'Управление командами';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return '';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('bs.trash3')
                ->method('remove')
                ->canSee($this->command->exists),
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, Command $command)
    {
        $request->validate([
            'command.name' => 'required',
            'command.response' => 'required',
        ]);

        $command->fill($request->get('command'));
        $command->save();

        Toast::info(__('Command was saved'));

        return redirect()->route('platform.commands');
    }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Command $command)
    {
        if (!$command->exists) {
            Toast::error(__('Command does not exist.'));
            return redirect()->route('platform.commands');
        }

        try {
            $command->delete();
            Toast::info(__('Command was removed'));
        } catch (\Exception $e) {
            Toast::error(__('Failed to remove command.'));
        }

        return redirect()->route('platform.commands');
    }
}
