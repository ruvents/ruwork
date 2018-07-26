<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket;

use RunetId\Client\RunetIdClient;

final class BasketFactory implements BasketFactoryInterface
{
    private $client;
    private $loaders;
    private $handlers;

    public function __construct(
        RunetIdClient $client,
        iterable $loaders,
        iterable $handlers
    ) {
        $this->client = $client;
        $this->loaders = $loaders;
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function create(int $payerRunetId): Basket
    {
        return new Basket($this->client, $payerRunetId, $this->loaders, $this->handlers);
    }
}
