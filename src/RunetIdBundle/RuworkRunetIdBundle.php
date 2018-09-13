<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle;

use Ruwork\RunetIdBundle\DependencyInjection\Compiler\AddBasketHandlersPass;
use Ruwork\RunetIdBundle\DependencyInjection\Compiler\AddBasketLoadersPass;
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
