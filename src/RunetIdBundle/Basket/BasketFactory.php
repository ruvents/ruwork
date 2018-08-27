<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket;

use Ruwork\RunetIdBundle\Client\RunetIdClients;

final class BasketFactory implements BasketFactoryInterface
{
    private $clients;
    private $loaders;
    private $handlers;

    public function __construct(
        RunetIdClients $clients,
        iterable $loaders,
        iterable $handlers
    ) {
        $this->clients = $clients;
        $this->loaders = $loaders;
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function create(int $payerRunetId, ?string $clientName = null): Basket
    {
        if (null === $clientName) {
            $client = $this->clients->getDefault();
        } else {
            $client = $this->clients->get($clientName);
        }

        return new Basket($client, $payerRunetId, $this->loaders, $this->handlers);
    }
}
