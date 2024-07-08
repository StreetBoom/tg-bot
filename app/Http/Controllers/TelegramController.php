<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Channel;
use DefStudio\Telegraph\Telegraph;

class TelegramController extends Controller
{
    public function getChannels()
    {
        $user = Auth::user();
        $channels = $user->channels;
        return response()->json($channels);
    }

    public function telegramCallback(Request $request)
    {
        $chatId = $request->query('chat_id');
        $user = Auth::user();

        if ($chatId && $user) {
            $bot = Telegraph::bot();
            $response = $bot->chat($chatId)->getChat();

            if ($response->ok()) {
                $chat = $response->result();

                Channel::updateOrCreate(
                    ['channel_id' => $chat->id()],
                    [
                        'user_id' => $user->id,
                        'channel_name' => $chat->title(),
                    ]
                );

                return redirect('/dashboard')->with('success', 'Канал успешно добавлен');
            }
        }

        return redirect('/dashboard')->with('error', 'Ошибка при добавлении канала');
    }
}
