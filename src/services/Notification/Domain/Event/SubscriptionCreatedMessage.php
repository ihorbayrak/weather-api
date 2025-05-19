<?php

declare(strict_types=1);

namespace App\services\Notification\Domain\Event;

class SubscriptionCreatedMessage
{
    public function __construct(public readonly string $email, public readonly string $token)
    {
    }
}