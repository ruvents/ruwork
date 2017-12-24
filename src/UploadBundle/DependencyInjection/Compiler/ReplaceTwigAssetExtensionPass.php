<?php

declare(strict_types=1);

namespace Ruwork\UploadBundle\DependencyInjection\Compiler;

use Ruwork\UploadBundle\Twig\Extension\AssetExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReplaceTwigAssetExtensionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has($name = 'twig.extension.assets')) {
            $container
                ->findDefinition($name)
                ->setClass(AssetExtension::class);
        }
    }
}
