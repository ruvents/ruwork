<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\TemplateI18nBundle\Controller\TemplateI18nController;
use Ruwork\TemplateI18nBundle\EventListener\TemplateAnnotationListener;
use Ruwork\TemplateI18nBundle\Localizer\TemplateLocalizer;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategy;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategyInterface;
use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolver;
use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolverInterface;

return function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->private();

    // Controller

    $services
        ->set(TemplateI18nController::class)
        ->public()
        ->args([
            '$localizer' => ref(TemplateLocalizer::class),
        ]);

    // EventListener

    $services
        ->set(TemplateAnnotationListener::class)
        ->args([
            '$resolver' => ref(LocalizedTemplateResolverInterface::class),
        ])
        ->tag('kernel.event_subscriber');

    // Localizer

    $services
        ->set(TemplateLocalizer::class)
        ->args([
            '$twig' => ref('twig'),
            '$resolver' => ref(LocalizedTemplateResolverInterface::class),
        ]);

    // NamingStrategy

    $services->set(NamingStrategy::class);

    $services->alias(NamingStrategyInterface::class, NamingStrategy::class);

    // Resolver

    $services
        ->set(LocalizedTemplateResolver::class)
        ->args([
            '$twig' => ref('twig'),
            '$namingStrategy' => ref(NamingStrategyInterface::class),
            '$defaultLocale' => '%kernel.default_locale%',
        ]);

    $services->alias(LocalizedTemplateResolverInterface::class, LocalizedTemplateResolver::class);
};
