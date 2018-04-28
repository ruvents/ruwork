<?php

declare(strict_types=1);

namespace Ruwork\SynchronizerBundle;

use Ruwork\SynchronizerBundle\DependencyInjection\Compiler\RegisterSynchronizationTypesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkSynchronizerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterSynchronizationTypesPass());
    }
}
