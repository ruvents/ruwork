<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Compiler\DependencyInjection;

use Ruwork\RunetIdBundle\Basket\Factory\BasketFactory;
use Ruwork\RunetIdBundle\Basket\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class AddBasketLoadersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(BasketFactory::class)) {
            return;
        }

        $tagged = $container->findTaggedServiceIds('ruwork_runet_id.basket_loader', true);
        $references = [];

        foreach ($tagged as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!\is_subclass_of($class, LoaderInterface::class)) {
                throw new \LogicException(\sprintf('Basket loader "%s" must implement "%s".', $class, LoaderInterface::class));
            }

            $references[$class::getClass()] = new Reference($id);
        }

        $container
            ->findDefinition(BasketFactory::class)
            ->setArgument('$loaders', ServiceLocatorTagPass::register($container, $references));
    }
}
