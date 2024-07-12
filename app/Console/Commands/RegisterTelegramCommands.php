<?php

namespace App\Console\Commands;

use App\Models\StaticCommand;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class RegisterTelegramCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:register-commands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register commands for the Telegram bot';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $bot = TelegraphBot::find(1);

        if (!$bot) {
            $this->error('Bot not found');
            return;
        }

        $commands = StaticCommand::all()->pluck('message', 'name')->toArray();

        $bot->registerCommands($commands)->send();

        $this->info('Commands registered successfully.');
    }
}
