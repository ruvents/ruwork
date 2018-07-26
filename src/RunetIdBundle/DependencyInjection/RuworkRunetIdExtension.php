<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\DependencyInjection;

use HWI\Bundle\OAuthBundle\HWIOAuthBundle;
use RunetId\Client\RunetIdClient;
use Ruwork\RunetIdBundle\Basket\HandlerInterface;
use Ruwork\RunetIdBundle\Basket\LoaderInterface;
use Ruwork\RunetIdBundle\Client\RunetIdClients;
use Ruwork\RunetIdBundle\HWIOAuth\ResourceOwner;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
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
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new PhpFileLoader($container, $locator);
        $loader->load('services.php');

        $container->registerForAutoconfiguration(LoaderInterface::class)
            ->setPrivate(true)
            ->addTag('ruwork_runet_id_basket.loader');

        $container->registerForAutoconfiguration(HandlerInterface::class)
            ->setPrivate(true)
            ->addTag('ruwork_runet_id_basket.handler');

        $clientReferences = [];

        foreach ($config['clients'] as $name => $clientConfig) {
            $clientReferences[] = $this->createClient($container, $name, $clientConfig);
        }

        $container
            ->getDefinition(RunetIdClients::class)
            ->setArgument(0, ServiceLocatorTagPass::register($container, $clientReferences));

        $clientAlias = new Alias('ruwork_runet_id.client.'.$config['default_client'], false);
        $container->setAlias(RunetIdClient::class, $clientAlias);

        if (!\class_exists(Validation::class)) {
            $container->removeDefinition('ruwork_runet_id.validator.unique_email');
        }
    }

    private function createClient(ContainerBuilder $container, string $name, array $config): Reference
    {
        $id = 'ruwork_runet_id.client.'.$name;

        $definition = new ChildDefinition('ruwork_runet_id.client');
        $container
            ->setDefinition($id, $definition)
            ->setArguments([
                '$key' => $config['key'],
                '$secret' => $config['secret'],
                '$apiUri' => $config['api_uri'],
                '$oauthUri' => $config['oauth_uri'],
                '$plugins' => \array_map(function (string $id) {
                    return new Reference($id);
                }, $config['plugins']),
                '$httpClient' => null === $config['http_client']
                    ? null
                    : new Reference('http_client'),
            ]);

        $clientReferences[$name] = new Reference($id);

        if (\class_exists(HWIOAuthBundle::class)) {
            $container
                ->register('ruwork_runet_id.oauth.'.$name, ResourceOwner::class)
                ->setPublic(false)
                ->setArgument('$client', new Reference($id));
        }

        return new Reference($id);
    }
}
