<?php

declare(strict_types=1);

namespace Ruwork\ObjectStoreBundle\DependencyInjection;

use Ruwork\ObjectStore\Type\StoreTypeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class RuworkObjectStoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new PhpFileLoader($container, $locator);
        $loader->load('services.php');

        $container->registerForAutoconfiguration(StoreTypeInterface::class)
            ->setPrivate(true)
            ->addTag('ruwork_object_store.type');
    }
}
