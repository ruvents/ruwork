<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle;

use Ruwork\RoutingToolsBundle\DependencyInjection\Compiler\ReplaceBridgeRoutingExtensionPass;
use Ruwork\RoutingToolsBundle\DependencyInjection\Compiler\RouteOptionalPrefixPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkRoutingToolsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ReplaceBridgeRoutingExtensionPass());
        $container->addCompilerPass(new RouteOptionalPrefixPass());
    }
}
