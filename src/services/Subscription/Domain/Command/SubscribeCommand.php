<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\Command;

use App\services\Subscription\Domain\ValueObject\Subscription\Email;
use App\services\Subscription\Domain\ValueObject\Subscription\Frequency;

class SubscribeCommand
{
    public function __construct(
        public readonly Email $email,
        public readonly string $city,
        public readonly Frequency $frequency
    ) {}
}