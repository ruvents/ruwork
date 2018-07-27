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

    public function get(string $name): RunetIdClient
    {
        return $this->container->get($name);
    }

    public function getDefault(): RunetIdClient
    {
        return $this->container->get($this->defaultName);
    }
}
