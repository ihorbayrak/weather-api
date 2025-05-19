<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\Exception;

use RuntimeException;

final class SubscriptionAlreadyConfirmedException extends RuntimeException
{
}