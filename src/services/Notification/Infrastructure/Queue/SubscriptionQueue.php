<?php

declare(strict_types=1);

namespace App\services\Notification\Infrastructure\Queue;

use App\services\Shared\Infrastructure\Queue\Queue;

class SubscriptionQueue extends Queue
{
    public const SUBSCRIBE_QUEUE_NAME = 'subscribe';
    public const SUBSCRIBE_EXCHANGE_NAME = 'subscribe_exchange';

    private const PREFIX = 'subscription_';

    public function __construct(
        ?string $queue,
        string $exchange = '',
        string $exchangeType = self::EXCHANGE_TYPE_FANOUT
    ) {
        if ($queue) {
            $queue = self::PREFIX . $queue;
        }

        $exchange = self::PREFIX . $exchange;

        parent::__construct($queue, $exchange, $exchangeType);
    }
}