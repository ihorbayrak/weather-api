<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\Command;

use App\services\Subscription\Domain\ValueObject\Subscription\Token;

class ConfirmCommand
{
    public function __construct(
        public readonly Token $token,
    ) {}
}