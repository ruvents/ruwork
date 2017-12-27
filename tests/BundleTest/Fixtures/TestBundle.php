<?php

declare(strict_types=1);

namespace Ruwork\BundleTest\Fixtures;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TestBundle extends Bundle
{
    private $withExtension;

    public function __construct(bool $withExtension = false)
    {
        $this->withExtension = $withExtension;
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return $this->withExtension ? new TestExtension() : null;
    }

    public function build(ContainerBuilder $container): void
    {
        $container->setParameter('test_bundle_parameter', true);
    }
}
