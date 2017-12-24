<?php

declare(strict_types=1);

namespace Ruwork\PaginatorBundle\DependencyInjection;

use Ruwork\PaginatorBundle\EventListener\PageOutOfRangeExceptionListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class RuworkPaginatorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(PageOutOfRangeExceptionListener::class)
            ->setPublic(false)
            ->addTag('kernel.event_subscriber');
    }
}
