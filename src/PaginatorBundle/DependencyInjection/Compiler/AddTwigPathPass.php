<?php

namespace Ruwork\PaginatorBundle\DependencyInjection\Compiler;

use Ruwork\Paginator\Paginator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddTwigPathPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('twig.loader.native_filesystem')) {
            return;
        }

        $path = dirname((new \ReflectionClass(Paginator::class))->getFileName()).'/Resources/templates';

        $container->getDefinition('twig.loader.native_filesystem')
            ->addMethodCall('addPath', [$path, 'RuworkPaginator']);
    }
}
