<?php

namespace App\Console\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class AddTelegraphBotsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-telegraph-bots-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Проверка на существование записей
        if (TelegraphBot::count() == 0) {
            TelegraphBot::create([
                'token' => '7437300242:AAHdjU8VqdhPivMFf2j0G4734V2Vt9ydUDg',
                //6938499795:AAHrh9TwdSyLsLHGS7Lj2uQjFZYvePDAveI Egor
                //6849458050:AAGHqFX7INpudojFXJRAwqk3yCaHeh5lFxY  Slava
                'name' => 'SvvkrusTestBot',
            ]);

            $this->info('The telegraph_bots table has been filled with initial data.');
        } else {
            $this->info('The telegraph_bots table already has data.');
        }

        return 0;
    }
}
