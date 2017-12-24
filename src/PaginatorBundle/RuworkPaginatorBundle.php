<?php

declare(strict_types=1);

namespace Ruwork\PaginatorBundle;

use Ruwork\PaginatorBundle\DependencyInjection\Compiler\AddTwigPathPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkPaginatorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AddTwigPathPass());
    }
}
