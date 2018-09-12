<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Client;

use Psr\Container\ContainerInterface;
use RunetId\Client\RunetIdClient;

final class RunetIdClients
{
    private $container;
    private $defaultName;

    public function __construct(ContainerInterface $container, string $defaultName)
    {
        $this->container = $container;
        $this->defaultName = $defaultName;
    }

    public function has(string $name): bool
    {
        return $this->container->has($name);
    }

    public function get(?string $name = null): RunetIdClient
    {
        return $this->container->get($name ?? $this->defaultName);
    }

    /**
     * @deprecated Deprecated since 0.12.0 and will be removed in 0.13.0.
     */
    public function getDefault(): RunetIdClient
    {
        return $this->get();
    }
}
