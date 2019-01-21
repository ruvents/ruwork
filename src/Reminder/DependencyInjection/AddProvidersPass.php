<?php

declare(strict_types=1);

namespace Ruwork\Reminder\DependencyInjection;

use Ruwork\Reminder\Manager\Reminder;
use Ruwork\Reminder\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class AddProvidersPass implements CompilerPassInterface
{
    private $tag;
    private $serviceId;

    public function __construct(
        string $tag = 'ruwork_reminder.provider',
        string $serviceId = Reminder::class
    ) {
        $this->tag = $tag;
        $this->serviceId = $serviceId;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has($this->serviceId)) {
            return;
        }

        $tagged = $container->findTaggedServiceIds($this->tag, true);
        $references = [];

        foreach ($tagged as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!is_subclass_of($class, ProviderInterface::class)) {
                throw new \LogicException(sprintf('Reminder items provider "%s" must implement "%s".', $class, ProviderInterface::class));
            }

            $references[$class::getName()] = new Reference($id);
        }

        $container
            ->findDefinition($this->serviceId)
            ->setArgument('$providers', ServiceLocatorTagPass::register($container, $references));
    }
}
