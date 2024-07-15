<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Command;

use App\Models\Command;
use App\Models\StaticCommand;
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
    public function query(StaticCommand $command): iterable
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
     * Create a new command.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'command.command' => 'required',
            'command.message' => 'required',
            'command.name' => 'nullable',
            'command.status' => 'required',
        ]);

        if ($request->hasFile('command.image')) {
            $file = $request->file('command.image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension(); // Генерация уникального имени файла
            $path = 'images/commands/' . $filename;
            $file->move(public_path('images/commands'), $filename);
            $data['command']['image'] = $path;
        }

        StaticCommand::create($data['command']);

        Toast::info('Команда была создана');

        return redirect()->route('platform.commands');
    }

    /**
     * Save the command.
     *
     * @param Request $request
     * @param StaticCommand $command
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, StaticCommand $command)
    {
        $data = $request->validate([
            'command.command' => 'required',
            'command.message' => 'required',
            'command.name' => 'nullable',
            'command.status' => 'required',
        ]);

        if ($request->hasFile('command.image')) {
            // Удаление старого изображения
            if ($command->image && file_exists(public_path($command->image))) {
                unlink(public_path($command->image));
            }

            $file = $request->file('command.image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'images/commands/' . $filename;
            $file->move(public_path('images/commands'), $filename);
            $data['command']['image'] = $path;
        }

        $command->fill($data['command']);
        $command->save();

        Toast::info(__('Команда была сохранена'));

        return redirect()->route('platform.commands');
    }

    /**
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function remove(StaticCommand $command)
    {
        try {
            // Удаление изображения, если оно существует
            if ($command->image && file_exists(public_path($command->image))) {
                unlink(public_path($command->image));
            }

            $command->delete();
            Toast::info(__('Команда была удалена'));
        } catch (\Exception $e) {
            Toast::error(__('Failed to remove command.'));
        }

        return redirect()->route('platform.commands');
    }
}
