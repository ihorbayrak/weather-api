<?php

declare(strict_types=1);

namespace App\services\Subscription\Infrastructure\Queue;

use App\services\Shared\Infrastructure\Queue\Queue;
use App\services\Subscription\Domain\Event\SubscriptionCreatedEvent;

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

    public static function subscribe(SubscriptionCreatedEvent $event): void
    {
        $self = new self(self::SUBSCRIBE_QUEUE_NAME, self::SUBSCRIBE_EXCHANGE_NAME);

        $self->publish(serialize($event));
    }
}