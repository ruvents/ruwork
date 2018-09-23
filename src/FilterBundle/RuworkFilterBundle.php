<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

use Ruwork\Filter\DependencyInjection\AddFilterTypesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkFilterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddFilterTypesPass());
    }
}
