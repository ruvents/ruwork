<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\DependencyInjection;

use Aws\Sdk;
use Http\Client\HttpClient;
use Ruwork\AwsBundle\Client\AwsSdks;
use Ruwork\AwsBundle\HttpHandler\HttplugHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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

        if (\class_exists(Sdk::class)) {
            $references = [];
            foreach ($config['sdks'] as $name => $value) {
                if (isset($value['http_handler'])) {
                    $value['http_handler'] = new Reference($value['http_handler']);
                }
                $references[$name] = $this->registerSdk($container, $name, $value);
            }

            $sdkAlias = new Alias('ruwork_aws.sdk.'.$config['default_sdk'], false);
            $container->setAlias(Sdk::class, $sdkAlias);
            $container->setAlias('ruwork_aws.sdk._default', $sdkAlias);

            $container
                ->findDefinition(AwsSdks::class)
                ->setArguments([
                    '$locator' => ServiceLocatorTagPass::register($container, $references),
                    '$defaultName' => $config['default_sdk'],
                ]);
        }

        if (!\interface_exists(HttpClient::class)) {
            $container->removeDefinition(HttplugHandler::class);
        }
    }

    public function registerSdk(ContainerBuilder $container, string $name, array $config): Reference
    {
        $id = 'ruwork_aws.sdk.'.$name;

        $container
            ->setDefinition($id, new Definition(Sdk::class))
            ->setPublic(false)
            ->setArgument(0, $config);

        return new Reference($id);
    }
}
