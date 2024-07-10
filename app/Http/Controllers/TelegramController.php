<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChannelsResource;
use App\Services\Channel\TelegramChannelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TelegramController extends Controller
{
    protected TelegramChannelService $telegramChannelService;
    public function __construct(TelegramChannelService $telegramChannelService)
    {
        $this->telegramChannelService = $telegramChannelService;
    }

    public function getChannelsByUserId(): JsonResponse
    {
        $user = Auth::user();
        if ($user) {
            $channels = $this->telegramChannelService->getChannelsByUserId($user);
            return response()->json(ChannelsResource::collection($channels));
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
