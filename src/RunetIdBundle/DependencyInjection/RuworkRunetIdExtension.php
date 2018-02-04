<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\DependencyInjection;

use RunetId\Client\RunetIdClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Validator\Validation;

final class RuworkRunetIdExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $clientReferences = [];

        foreach ($config['clients'] as $name => $clientConfig) {
            $id = 'ruwork_runet_id.client.'.$name;

            $definition = new ChildDefinition('ruwork_runet_id.client');
            $container
                ->setDefinition($id, $definition)
                ->setArguments([
                    '$key' => $clientConfig['key'],
                    '$secret' => $clientConfig['secret'],
                    '$apiUri' => $clientConfig['api_uri'],
                    '$oauthUri' => $clientConfig['oauth_uri'],
                    '$plugins' => array_map(function (string $id) {
                        return new Reference($id);
                    }, $clientConfig['plugins']),
                    '$httpClient' => null === $clientConfig['http_client']
                        ? null
                        : new Reference('http_client'),
                ]);

            $clientReferences[$name] = new Reference($id);
        }

        $container->getDefinition('ruwork_runet_id.client_container')
            ->setArgument(0, $clientReferences);

        $defaultClientAlias = new Alias('ruwork_runet_id.client.'.$config['default_client'], false);
        $container->setAlias(RunetIdClient::class, $defaultClientAlias);

        if (!class_exists(Validation::class)) {
            $container->removeDefinition('ruwork_runet_id.validator.unique_email');
        }
    }
}
