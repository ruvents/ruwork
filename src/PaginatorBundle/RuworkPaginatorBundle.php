<?php

namespace Ruwork\PaginatorBundle;

use Ruwork\PaginatorBundle\DependencyInjection\Compiler\AddTwigPathPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkPaginatorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddTwigPathPass());
    }
}
