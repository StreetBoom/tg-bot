<?php

declare(strict_types=1);

namespace App\Orchid\Screens\TelegramUser;

use App\Models\TelegramUser;
use App\Orchid\Layouts\TelegramUser\TelegramUserListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class TelegramUserListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'users' => TelegramUser::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Список пользователей';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
//            Link::make(__('Добавить'))
//                ->icon('bs.plus-circle')
//                ->href(route('platform.tg-users.create')),
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
            TelegramUserListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        TelegramUser::findOrFail($request->get('id'))->delete();

        Toast::info(__('Пользователь удален'));
    }
}
