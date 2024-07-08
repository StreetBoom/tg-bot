<?php

namespace App\DTO;

class BaseDTO implements BaseDTOInterface
{
    /**
     * @param array|null $initial
     */
    public function __construct(?array $initial = null)
    {
        if (is_array($initial)) {
            foreach ($initial as $key => $value) {
                try {
                    $property = new \ReflectionProperty(static::class, $key);

                    if ($property->getType()->getName() === 'bool' && ! is_null($value)) {
                        $value = mb_strtolower($value);

                        $value = ($value === 'true') || ($value === '1');
                    }

                    if ($property->getType()->getName() === 'array' && ! is_array($value) && ! is_null($value)) {
                        $value = [$value];
                    }

                    $this->$key = $value;
                } catch (\Throwable $exception) {
                    continue;
                }
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
