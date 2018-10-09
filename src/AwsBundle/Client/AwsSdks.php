<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\Client;

use Aws\Sdk;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class AwsSdks
{
    private $locator;
    private $defaultName;

    public function __construct(ServiceLocator $locator, string $defaultName)
    {
        $this->locator = $locator;
        $this->defaultName = $defaultName;
    }

    public function has(string $name): bool
    {
        return $this->locator->has($name);
    }

    public function get(?string $name = null): Sdk
    {
        return $this->locator->get($name ?? $this->defaultName);
    }
}
