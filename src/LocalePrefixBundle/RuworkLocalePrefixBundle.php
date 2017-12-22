<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle;

use Ruwork\LocalePrefixBundle\DependencyInjection\Compiler\RouterAliasPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkLocalePrefixBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RouterAliasPass());
    }
}
