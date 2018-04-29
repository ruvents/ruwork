<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle;

use Ruwork\FeatureBundle\DependencyInjection\Compiler\RegisterFeaturesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkFeatureBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterFeaturesPass());
    }
}
