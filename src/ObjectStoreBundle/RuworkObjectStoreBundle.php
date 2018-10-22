<?php

declare(strict_types=1);

namespace Ruwork\ObjectStoreBundle;

use Ruwork\ObjectStore\DependencyInjection\AddStoreTypesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkObjectStoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddStoreTypesPass());
    }
}
