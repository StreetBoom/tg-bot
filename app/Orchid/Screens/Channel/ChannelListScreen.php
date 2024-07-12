<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Channel;

use App\Models\Channel;
use App\Orchid\Layouts\Channel\ChannelListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ChannelListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'channels' => Channel::with('users')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Список каналов';
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
            ChannelListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        Channel::findOrFail($request->get('id'))->delete();

        Toast::info(__('Канал удален'));
    }
}
