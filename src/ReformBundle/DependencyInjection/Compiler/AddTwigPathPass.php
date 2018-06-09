<?php

declare(strict_types=1);

namespace Ruwork\ReformBundle\DependencyInjection\Compiler;

use Ruwork\Reform\Extension\CheckboxTypeFalseValueExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AddTwigPathPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('twig.loader.native_filesystem')) {
            return;
        }

        $file = (new \ReflectionClass(CheckboxTypeFalseValueExtension::class))->getFileName();
        $path = dirname(dirname($file)).'/Resources/views';

        $container
            ->findDefinition('twig.loader.native_filesystem')
            ->addMethodCall('addPath', [$path, 'RuworkReform']);
    }
}
