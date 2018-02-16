<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

use Ruwork\FilterBundle\DependencyInjection\Compiler\RegisterFilterTypesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkFilterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterFilterTypesPass());
    }
}
