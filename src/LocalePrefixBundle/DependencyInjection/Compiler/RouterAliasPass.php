<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\DependencyInjection\Compiler;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RouterAliasPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasAlias('router')
            || Router::class !== $container->findDefinition('router')->getClass()
        ) {
            $container->removeDefinition('ruwork_locale_prefix.default_router');

            return;
        }

        $public = $container->getAlias('router')->isPublic();
        $container->setAlias('router', new Alias('ruwork_locale_prefix.default_router', $public));
    }
}
