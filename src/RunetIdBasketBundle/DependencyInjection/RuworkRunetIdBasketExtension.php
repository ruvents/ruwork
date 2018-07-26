<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBasketBundle\DependencyInjection;

use Ruwork\RunetIdBasketBundle\Handler\HandlerInterface;
use Ruwork\RunetIdBasketBundle\Loader\LoaderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class RuworkRunetIdBasketExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new PhpFileLoader($container, $locator);
        $loader->load('services.php');

        $container->registerForAutoconfiguration(LoaderInterface::class)
            ->setPrivate(true)
            ->addTag('ruwork_runet_id_basket.loader');

        $container->registerForAutoconfiguration(HandlerInterface::class)
            ->setPrivate(true)
            ->addTag('ruwork_runet_id_basket.handler');
    }
}
