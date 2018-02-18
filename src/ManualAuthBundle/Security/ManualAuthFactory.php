<?php

declare(strict_types=1);

namespace Ruwork\ManualAuthBundle\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

final class ManualAuthFactory implements SecurityFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint): array
    {
        $listenerId = 'ruwork.manual_auth.listener.'.$id;

        $container->setDefinition($listenerId, new ChildDefinition('ruwork_manual_auth.listener'))
            ->setArgument('$firewallConfig', new Reference('security.firewall.map.config.'.$id))
            ->addTag('kernel.event_listener', [
                'event' => KernelEvents::RESPONSE,
                'priority' => -4000,
            ])
            ->addTag('security.remember_me_aware', [
                'id' => $id,
                'provider' => $userProvider,
            ]);

        return [
            'ruwork_manual_auth.provider',
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
