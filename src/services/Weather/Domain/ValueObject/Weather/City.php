<?php

declare(strict_types=1);

namespace App\services\Weather\Domain\ValueObject\Weather;

final class City
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}