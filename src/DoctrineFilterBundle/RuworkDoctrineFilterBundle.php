<?php

namespace Ruwork\DoctrineFilterBundle;

use Ruwork\DoctrineFilterBundle\DependencyInjection\Compiler\FilterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkDoctrineFilterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FilterPass());
    }
}
