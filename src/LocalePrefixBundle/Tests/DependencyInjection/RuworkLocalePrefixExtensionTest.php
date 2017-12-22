<?php

declare(strict_types=1);

namespace Ruwork\LocalePrefixBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Ruwork\LocalePrefixBundle\DependencyInjection\RuworkLocalePrefixExtension;
use Symfony\Component\DependencyInjection\Reference;

class RuworkLocalePrefixExtensionTest extends AbstractExtensionTestCase
{
    public function test(): void
    {
        $this->load([
            'locales' => $locales = ['ru', 'en'],
            'default_locale' => $defaultLocale = 'ru',
        ]);

        $this->assertContainerBuilderHasService('ruwork_locale_prefix.loader_decorator');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('ruwork_locale_prefix.loader_decorator',
            '$loader', new Reference('ruwork_locale_prefix.loader_decorator.inner'));
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('ruwork_locale_prefix.loader_decorator',
            '$locales', $locales);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('ruwork_locale_prefix.loader_decorator',
            '$defaultLocale', $defaultLocale);

        $this->assertContainerBuilderHasService('ruwork_locale_prefix.default_router');
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall('ruwork_locale_prefix.default_router',
            'setRequestStack', [new Reference('request_stack')]);
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall('ruwork_locale_prefix.default_router',
            'setDefaultLocale', [$defaultLocale]);
    }

    protected function getContainerExtensions()
    {
        return [
            new RuworkLocalePrefixExtension(),
        ];
    }
}
