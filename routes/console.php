<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('test', function () {
    $bot = \DefStudio\Telegraph\Models\TelegraphBot::find(1);
    $bot->registerCommands([
        'start' => 'Авторизация',
    ])->send();
});
