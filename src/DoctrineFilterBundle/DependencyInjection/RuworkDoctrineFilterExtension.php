<?php

namespace Ruwork\DoctrineFilterBundle\DependencyInjection;

use Ruwork\DoctrineFilterBundle\FilterManager;
use Ruwork\DoctrineFilterBundle\Type\FilterTypeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class RuworkDoctrineFilterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->autowire(FilterManager::class)
            ->setPublic(false);

        $container->registerForAutoconfiguration(FilterTypeInterface::class)
            ->setPublic(false)
            ->addTag('ruwork_doctrine_filter_type');
    }
}
