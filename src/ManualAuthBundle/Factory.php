<?php

declare(strict_types=1);

namespace Ruwork\ManualAuthBundle;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class Factory implements SecurityFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint): array
    {
        if (!$container->has(Provider::class)) {
            $container->register(Provider::class)
                ->setPublic(false);
        }

        if (!$container->has(ManualAuthenticator::class)) {
            $container->register(ManualAuthenticator::class)
                ->setPublic(false);
        }

        $listenerId = 'ruwork.manual_auth.listener.'.$id;

        $container->register($listenerId, Listener::class)
            ->setArguments([
                '$authenticator' => new Reference(ManualAuthenticator::class),
                '$firewallConfig' => new Reference('security.firewall.map.config.'.$id),
                '$manager' => new Reference('security.auth.manager'),
            ])
            ->setPublic(false)
            ->addTag('kernel.event_listener', ['event' => KernelEvents::RESPONSE, 'priority' => -4000])
            ->addTag('security.remember_me_aware', ['id' => $id, 'provider' => $userProvider]);

        return [
            Provider::class,
            $listenerId,
            $defaultEntryPoint,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(): string
    {
        return 'pre_auth';
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return 'manual';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeDefinition $builder): void
    {
    }
}
