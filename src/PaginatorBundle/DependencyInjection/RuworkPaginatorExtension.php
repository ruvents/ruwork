<?php

namespace Ruwork\PaginatorBundle\DependencyInjection;

use Ruwork\PaginatorBundle\EventListener\PageOutOfRangeExceptionListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class RuworkPaginatorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->register(PageOutOfRangeExceptionListener::class)
            ->setPublic(false)
            ->addTag('kernel.event_subscriber');
    }
}
