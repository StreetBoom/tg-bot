<?php

declare(strict_types=1);

namespace App\Orchid\Screens\TelegramUser;

use App\Models\Command;
use App\Models\TelegramUser;
use App\Orchid\Layouts\TelegramUser\TelegramUserEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class TelegramUserEditScreen extends Screen
{
    /**
     * @var Command
     */
    public $user;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(TelegramUser $user): iterable
    {
        return [
            'user'  => $user,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->user->exists ? 'Редактирование пользователя' : 'Создание пользователя';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return $this->user->exists ? [
            Button::make('Удалить')
                ->icon('trash')
                ->confirm('Вы точно хотите удалить этого пользователя?')
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
            TelegramUserEditLayout::class
        ];
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request)
    {
        $userData = $request->user;
        TelegramUser::create($userData);
        Toast::info('Пользователь создан');
        return redirect()->route('platform.tg-users');
    }


    /**
     * @return RedirectResponse
     */
    public function save(Request $request, TelegramUser $user)
    {
//        $request->validate([
//            'user.name' => 'required',
//            'user.email' => 'required',
//            'user.telegram_id' => 'required',
//            'user.username' => 'required',
//        ]);

        $user->fill($request->get('user'));
        $user->save();

        Toast::info(__('Пользователь был сохранен'));

        return redirect()->route('platform.tg-users');
    }

    /**
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function remove(TelegramUser $user)
    {
        try {
            $user->delete();
            Toast::info(__('Пользователь был удален'));
        } catch (\Exception $e) {
            Toast::error(__('Failed to remove command.'));
        }

        return redirect()->route('platform.tg-users');
    }
}
