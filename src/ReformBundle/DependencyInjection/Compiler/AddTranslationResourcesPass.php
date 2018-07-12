<?php

declare(strict_types=1);

namespace Ruwork\ReformBundle\DependencyInjection\Compiler;

use Ruwork\Reform\Extension\CheckboxTypeFalseValueExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AddTranslationResourcesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('translator')) {
            return;
        }

        $translator = $container->findDefinition('translator');

        $reflection = new \ReflectionClass(CheckboxTypeFalseValueExtension::class);
        $dir = \dirname(\dirname($reflection->getFileName()));
        $files = \glob($dir.'/Resources/translations/*');

        foreach ($files as $file) {
            list($domain, $locale, $format) = \explode('.', \basename($file), 3);
            $translator->addMethodCall('addResource', [$format, $file, $locale, $domain]);
        }
    }
}
