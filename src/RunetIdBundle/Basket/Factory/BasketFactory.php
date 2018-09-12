<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Factory;

use Psr\Container\ContainerInterface;
use Ruwork\RunetIdBundle\Basket\Basket\Basket;
use Ruwork\RunetIdBundle\Basket\Basket\BasketInterface;
use Ruwork\RunetIdBundle\Client\RunetIdClients;

final class BasketFactory implements BasketFactoryInterface
{
    private $clients;
    private $loaders;
    private $handlers;

    public function __construct(
        RunetIdClients $clients,
        ContainerInterface $loaders,
        ContainerInterface $handlers
    ) {
        $this->clients = $clients;
        $this->loaders = $loaders;
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function create(int $payerRunetId, ?string $clientName = null): BasketInterface
    {
        return new Basket(
            $this->clients->get($clientName),
            $payerRunetId,
            $this->loaders,
            $this->handlers
        );
    }
}
