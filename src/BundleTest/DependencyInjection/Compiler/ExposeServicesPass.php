<?php

declare(strict_types=1);

namespace Ruwork\BundleTest\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExposeServicesPass implements CompilerPassInterface
{
    private $services = [];

    public function addService(string $id): void
    {
        $this->services[] = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        foreach ($this->services as $id) {
            if ($container->has($id)) {
                $container->findDefinition($id)->setPublic(true);
            }
        }
    }
}
