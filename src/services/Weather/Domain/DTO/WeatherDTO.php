<?php

declare(strict_types=1);

namespace App\services\Weather\Domain\DTO;

class WeatherDTO
{
    public function __construct(
        public readonly float $temperature,
        public readonly int $humidity,
        public readonly string $description
    ) {}
}