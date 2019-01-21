<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle\DependencyInjection\Compiler;

use Ruwork\FeatureBundle\FeatureInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterFeaturesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('ruwork_feature.checker')) {
            return;
        }

        $features = $container->findTaggedServiceIds('ruwork_feature', true);
        $featureRefs = [];

        foreach ($features as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!is_subclass_of($class, FeatureInterface::class)) {
                throw new \InvalidArgumentException(sprintf('Feature "%s" must implement "%s".', $class, FeatureInterface::class));
            }

            $featureRefs[$class::getName()] = new Reference($id);
        }

        $container
            ->findDefinition('ruwork_feature.checker')
            ->setArgument('$features', ServiceLocatorTagPass::register($container, $featureRefs));
    }
}
