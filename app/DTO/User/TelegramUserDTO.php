<?php

namespace App\DTO\User;

use App\DTO\BaseDTO;


class TelegramUserDTO extends BaseDTO
{
    public string $name;

    public int $telegram_id;

    public ?string $username;

    public ?string $avatar;
}
