<?php

namespace App\DTO;


interface BaseDTOInterface
{
    /**
     * @param array|null $initial
     */
    public function __construct(?array $initial = null);

    /**
     * @return array
     */
    public function toArray(): array;
}
