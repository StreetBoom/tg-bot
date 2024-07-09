<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChannelsResource;
use App\Services\Channel\TelegramChannelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Channel;
use DefStudio\Telegraph\Telegraph;

class TelegramController extends Controller
{
    protected $telegramChannelService;
    public function __construct(TelegramChannelService $telegramChannelService)
    {
        $this->telegramChannelService = $telegramChannelService;
    }

    public function getChannelsByUserId()
    {
        $user = auth()->user();
        $channels = $this->telegramChannelService->getChannelsByUserId($user);
        return response()->json(ChannelsResource::collection($channels));
    }
}
