<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Ruwork\TemplateI18nBundle\EventListener\TemplateAnnotationListener;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategy;
use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolver;
use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolverInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal
 */
class RuworkTemplateI18nExtensionTest extends AbstractExtensionTestCase
{
    public function testDefault(): void
    {
        $this->load([
            'naming' => [
                'locale_suffix_pattern' => 'locale_suffix_pattern',
                'extension_pattern' => 'extension_pattern',
                'no_suffix_locale' => 'no_suffix_locale',
            ],
        ]);
        $this->compile();

        $this->assertContainerBuilderHasService(
            'ruwork_template_i18n.naming_strategy',
            NamingStrategy::class
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.naming_strategy',
            '$localeSuffixPattern',
            'locale_suffix_pattern'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.naming_strategy',
            '$extensionPattern',
            'extension_pattern'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.naming_strategy',
            '$noSuffixLocale',
            'no_suffix_locale'
        );

        $this->assertContainerBuilderHasService(
            'ruwork_template_i18n.resolver',
            LocalizedTemplateResolver::class
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.resolver',
            '$namingStrategy',
            new Reference('ruwork_template_i18n.naming_strategy')
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.resolver',
            '$twig',
            new Reference('twig')
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.resolver',
            '$requestStack',
            new Reference('request_stack')
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.resolver',
            '$defaultLocale',
            '%kernel.default_locale%'
        );

        $this->assertContainerBuilderHasAlias(
            LocalizedTemplateResolverInterface::class,
            'ruwork_template_i18n.resolver'
        );

        $this->assertContainerBuilderHasService(
            'ruwork_template_i18n.annotation_listener',
            TemplateAnnotationListener::class
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.annotation_listener',
            '$resolver',
            new Reference(LocalizedTemplateResolverInterface::class)
        );
    }

    public function testCustomStrategy()
    {
        $this->load([
            'naming' => [
                'service' => 'service',
            ],
        ]);
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'ruwork_template_i18n.resolver',
            '$namingStrategy',
            new Reference('service')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new RuworkTemplateI18nExtension(),
        ];
    }
}
