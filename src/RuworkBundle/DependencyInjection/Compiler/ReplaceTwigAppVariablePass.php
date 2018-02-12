<?php

namespace Ruvents\RuworkBundle\DependencyInjection\Compiler;

use Ruvents\RuworkBundle\Twig\AppVariable;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class ReplaceTwigAppVariablePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has($name = 'twig.app_variable')) {
            return;
        }

        $container
            ->findDefinition($name)
            ->setClass(AppVariable::class)
            ->addMethodCall('setFirewallMap', [
                new Reference('security.firewall.map', ContainerInterface::IGNORE_ON_INVALID_REFERENCE),
            ]);
    }
}
