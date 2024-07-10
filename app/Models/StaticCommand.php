<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;


class StaticCommand extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected static function booted()
    {
        static::saved(function ($staticCommand) {
            Artisan::call('telegram:register-commands');
        });

        static::deleted(function ($staticCommand) {
            Artisan::call('telegram:register-commands');
        });
    }
}
