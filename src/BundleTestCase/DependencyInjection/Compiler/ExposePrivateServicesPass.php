<?php

declare(strict_types=1);

namespace Ruwork\BundleTestCase\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExposePrivateServicesPass implements CompilerPassInterface
{
    private $services;

    public function __construct(array $services)
    {
        $this->services = $services;
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
