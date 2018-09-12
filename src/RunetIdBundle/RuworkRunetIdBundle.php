<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle;

use Ruwork\RunetIdBundle\Compiler\DependencyInjection\AddBasketHandlersPass;
use Ruwork\RunetIdBundle\Compiler\DependencyInjection\AddBasketLoadersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkRunetIdBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new AddBasketLoadersPass())
            ->addCompilerPass(new AddBasketHandlersPass());
    }
}
