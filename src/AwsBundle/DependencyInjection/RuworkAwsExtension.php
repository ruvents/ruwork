<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\DependencyInjection;

use Aws\Sdk;
use Http\Client\HttpClient;
use Ruwork\AwsBundle\HttpHandler\HttplugHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class RuworkAwsExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new PhpFileLoader($container, $locator);
        $loader->load('services.php');

        if (isset($config['http_handler'])) {
            $config['http_handler'] = new Reference($config['http_handler']);
        }

        $container
            ->findDefinition(Sdk::class)
            ->setArgument(0, $config);

        if (!\interface_exists(HttpClient::class)) {
            $container->removeDefinition(HttplugHandler::class);
        }
    }
}
