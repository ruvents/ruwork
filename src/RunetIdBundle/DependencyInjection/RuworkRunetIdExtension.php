<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\DependencyInjection;

use HWI\Bundle\OAuthBundle\HWIOAuthBundle;
use RunetId\Client\RunetIdClient;
use Ruwork\RunetIdBundle\Basket\Handler\HandlerInterface;
use Ruwork\RunetIdBundle\Basket\Loader\LoaderInterface;
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
            ->addTag('ruwork_runet_id.basket_loader');

        $container->registerForAutoconfiguration(HandlerInterface::class)
            ->setPrivate(true)
            ->addTag('ruwork_runet_id.basket_handler');

        $clientReferences = [];

        foreach ($config['clients'] as $name => $clientConfig) {
            $clientReferences[$name] = $this->registerClient($container, $name, $clientConfig);
        }

        $clientAlias = new Alias('ruwork_runet_id.client.'.$config['default_client'], false);
        $container->setAlias(RunetIdClient::class, $clientAlias);
        $container->setAlias('ruwork_runet_id.client._default', $clientAlias);

        if (class_exists(HWIOAuthBundle::class)) {
            foreach ($clientReferences as $name => $reference) {
                $container
                    ->register('ruwork_runet_id.oauth.'.$name, ResourceOwner::class)
                    ->setPublic(false)
                    ->setArgument('$client', $reference);
            }

            $oauthAlias = new Alias('ruwork_runet_id.oauth.'.$config['default_client'], false);
            $container->setAlias('ruwork_runet_id.oauth._default', $oauthAlias);
        }

        $container
            ->findDefinition(RunetIdClients::class)
            ->setArguments([
                '$container' => ServiceLocatorTagPass::register($container, $clientReferences),
                '$defaultName' => $config['default_client'],
            ]);

        if (!class_exists(Validation::class)) {
            $container->removeDefinition('ruwork_runet_id.validator.unique_email');
        }
    }

    private function registerClient(ContainerBuilder $container, string $name, array $config): Reference
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
                '$plugins' => array_map(function (string $id) {
                    return new Reference($id);
                }, $config['plugins']),
                '$httpClient' => null === $config['http_client'] ? null : new Reference('http_client'),
            ]);

        return new Reference($id);
    }
}
