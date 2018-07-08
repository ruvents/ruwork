<?php

declare(strict_types=1);

namespace Ruwork\ReformBundle;

use Ruwork\ReformBundle\DependencyInjection\Compiler\AddTranslationResourcesPass;
use Ruwork\ReformBundle\DependencyInjection\Compiler\AddTwigPathPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkReformBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddTwigPathPass());
        $container->addCompilerPass(new AddTranslationResourcesPass());
    }
}
