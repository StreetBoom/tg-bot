<?php

namespace App\DTO;


class BaseRequestDTO extends BaseDTO
{
    public ?int $limit = null;

    public ?string $sort = null;

    public ?string $direction = null;
}
