<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\Client;

use Aws\Sdk;
use Psr\Container\ContainerInterface;

final class AwsSdks
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

    public function get(?string $name = null): Sdk
    {
        return $this->container->get($name ?? $this->defaultName);
    }
}
