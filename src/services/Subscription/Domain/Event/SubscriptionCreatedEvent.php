<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\Event;

class SubscriptionCreatedEvent
{
    public function __construct(public readonly string $email, public readonly string $token)
    {
    }
}