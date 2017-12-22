<?php

namespace Ruwork\ApiBundle\DependencyInjection;

use Ruwork\ApiBundle\Controller\DocsController;
use Ruwork\ApiBundle\DocsExtractor;
use Ruwork\ApiBundle\EventListener\ApiListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class RuworkApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->autowire(DocsExtractor::class)
            ->setPublic(false);

        $container->register(ApiListener::class)
            ->setPublic(false)
            ->addTag('kernel.event_subscriber');

        $container->autowire(DocsController::class);
    }
}
