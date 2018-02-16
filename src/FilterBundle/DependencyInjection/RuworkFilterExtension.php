<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle\DependencyInjection;

use Ruwork\FilterBundle\FilterTypeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class RuworkFilterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $container->registerForAutoconfiguration(FilterTypeInterface::class)
            ->setPublic(false)
            ->addTag('ruwork_filter.type');
    }
}
