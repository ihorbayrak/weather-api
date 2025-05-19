<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\ValueObject\Subscription;

enum Frequency: string
{
    case HOURLY = 'hourly';
    case DAILY = 'daily';
}
