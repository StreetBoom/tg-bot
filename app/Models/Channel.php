<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Screen\AsSource;


class Channel extends Model
{
    use HasFactory, AsSource;

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(TelegramUser::class, 'user_channels', 'channel_id', 'user_id')->withTimestamps();
    }

    protected $casts = [
        'has_permission' => 'boolean',
    ];

}
