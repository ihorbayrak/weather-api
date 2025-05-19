<?php

declare(strict_types=1);

namespace App\services\Shared\Infrastructure\Queue;

use Bunny\Channel;
use Bunny\Client;

class Queue
{
    public const EXCHANGE_TYPE_DIRECT = 'direct';
    public const EXCHANGE_TYPE_FANOUT = 'fanout';

    public const PARAM_HEARTBEAT = 'heartbeat';
    public const PARAM_READ_WRITE_TIMEOUT = 'read_write_timeout';

    public const DEFAULT_READ_WRITE_TIMEOUT = 1;
    public const DEFAULT_HEARTBEAT = 60;

    protected ?Client $client = null;
    protected ?Channel $channel = null;
    protected ?string $queue;
    protected string $exchange;
    protected string $exchangeType;

    public function __construct(
        ?string $queue,
        string $exchange = '',
        string $exchangeType = self::EXCHANGE_TYPE_DIRECT,
    ) {
        $this->queue = $queue;
        $this->exchange = $exchange;
        $this->exchangeType = $exchangeType;
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function publish(string $data, array $options = []): void
    {
        $options = array_merge($options, [
            self::PARAM_HEARTBEAT => $_ENV['RABBITMQ_HEARTBEAT'] ?? self::DEFAULT_HEARTBEAT,
            self::PARAM_READ_WRITE_TIMEOUT => $_ENV['RABBITMQ_READ_WRITE_TIMEOUT'] ?? self::DEFAULT_READ_WRITE_TIMEOUT,
        ]);

        $channel = $this->getChannel($options);
        $this->createQueue($channel);

        $publishOptions = [];

        $channel->publish($data, $publishOptions, $this->exchange, $this->queue . "_route");

        $channel->close();
    }

    public function listen(callable $callback, array $options = []): void
    {
        if (!$this->queue) {
            return;
        }

        $channel = $this->getChannel($options);

        $this->createQueue($channel);

        $channel->run($callback, $this->queue);
    }

    protected function getChannel(array $options): Channel
    {
        return $this->connect($options)->channel();
    }

    protected function createQueue(Channel $channel): void
    {
        if ($this->exchange) {
            $channel->exchangeDeclare(
                $this->exchange,
                $this->exchangeType,
                false,
                true,
            );
        }

        if ($this->queue) {
            $channel->queueDeclare(
                $this->queue,
                false,
                true,
            );

            $channel->queueBind($this->queue, $this->exchange, $this->queue . "_route");
        }
    }

    protected function connect(array $options = []): Client
    {
        if ($this->client) {
            return $this->client;
        }

        $heartbeat = $options[self::PARAM_HEARTBEAT] ?? self::DEFAULT_HEARTBEAT;
        $readWriteTimeout = $options[self::PARAM_READ_WRITE_TIMEOUT] ?? self::DEFAULT_READ_WRITE_TIMEOUT;

        $connection = [
            'host' => $_ENV['RABBITMQ_HOST'] ?? 'localhost',
            'port' => $_ENV['RABBITMQ_PORT'] ?? 5672,
            'vhost' => $_ENV['RABBITMQ_VHOST'] ?? '/',
            'user' => $_ENV['RABBITMQ_USER'] ?? 'guest',
            'password' => $_ENV['RABBITMQ_PASSWORD'] ?? 'guest',
            'async' => false,
            'timeout' => 1,
            self::PARAM_READ_WRITE_TIMEOUT => (int)$readWriteTimeout,
            self::PARAM_HEARTBEAT => (int)$heartbeat,
        ];

        $this->client = new Client($connection);
        $this->client->connect();

        return $this->client;
    }

    protected function disconnect(): void
    {
        if (!$this->client) {
            return;
        }

        $this->client->disconnect();

        $this->client = null;
    }
}