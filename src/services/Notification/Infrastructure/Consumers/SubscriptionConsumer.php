<?php

declare(strict_types=1);

namespace App\services\Notification\Infrastructure\Consumers;

use App\services\Notification\Domain\Event\SubscriptionCreatedMessage;
use App\services\Notification\Infrastructure\Queue\SubscriptionQueue;
use Bunny\Channel;
use Bunny\Exception\ClientException;
use Bunny\Message;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Throwable;

#[AsCommand(
    name: 'consumer:subscription',
)]
class SubscriptionConsumer extends Command
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $queue = new SubscriptionQueue(SubscriptionQueue::SUBSCRIBE_QUEUE_NAME, SubscriptionQueue::SUBSCRIBE_EXCHANGE_NAME);

        $queue->listen(function(Message $message, Channel $channel) use ($output) {
            try {
                /** @var SubscriptionCreatedMessage $event */
                $event = unserialize($message->content);

                $url = $this->urlGenerator->generate(
                    'api_confirm',
                    ['token' => $event->token],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $output->writeln("You have successfully subscribed with email {$event->email}. Click on that link and confirm your email {$url}");

                $channel->ack($message);
            } catch (ClientException $exception) {
                $channel->reject($message);
            } catch (Throwable $exception) {
                $channel->ack($message);
            }
        });

        return Command::SUCCESS;
    }
}