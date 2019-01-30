<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\DependencyInjection\Compiler;

use Ruwork\RunetIdBundle\Basket\Factory\BasketFactory;
use Ruwork\RunetIdBundle\Basket\Handler\HandlerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class AddBasketHandlersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(BasketFactory::class)) {
            return;
        }

        $tagged = $container->findTaggedServiceIds('ruwork_runet_id.basket_handler', true);
        $references = [];

        foreach ($tagged as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!is_subclass_of($class, HandlerInterface::class)) {
                throw new \LogicException(sprintf('Basket handler "%s" must implement "%s".', $class, HandlerInterface::class));
            }

            $references[$class] = new Reference($id);
        }

        $container
            ->findDefinition(BasketFactory::class)
            ->setArgument('$handlers', ServiceLocatorTagPass::register($container, $references));
    }
}
