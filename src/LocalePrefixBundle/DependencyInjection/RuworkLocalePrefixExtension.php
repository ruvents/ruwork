<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class RuworkLocalePrefixExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $container->getDefinition('ruwork_locale_prefix.loader_decorator')
            ->setArgument('$locales', $config['locales'])
            ->setArgument('$defaultLocale', $config['default_locale']);

        $container->getDefinition('ruwork_locale_prefix.default_router')
            ->addMethodCall('setDefaultLocale', [$config['default_locale']]);
    }
}
