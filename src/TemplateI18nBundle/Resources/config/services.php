<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\TemplateI18nBundle\Controller\TemplateI18nController;
use Ruwork\TemplateI18nBundle\EventListener\TemplateAnnotationListener;
use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategy;
use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolver;
use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolverInterface;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->private();

    $services->set('ruwork_template_i18n.naming_strategy')
        ->class(NamingStrategy::class);

    $services->set('ruwork_template_i18n.resolver')
        ->class(LocalizedTemplateResolver::class)
        ->args([
            '$namingStrategy' => ref('ruwork_template_i18n.naming_strategy'),
            '$twig' => ref('twig'),
            '$requestStack' => ref('request_stack'),
            '$defaultLocale' => '%kernel.default_locale%',
        ]);

    $services->alias(LocalizedTemplateResolverInterface::class, 'ruwork_template_i18n.resolver');

    $services->set('ruwork_template_i18n.annotation_listener')
        ->class(TemplateAnnotationListener::class)
        ->args([
            '$resolver' => ref(LocalizedTemplateResolverInterface::class),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('ruwork_template_i18n.controller')
        ->class(TemplateI18nController::class)
        ->public()
        ->args([
            '$twig' => ref('twig'),
            '$resolver' => ref(LocalizedTemplateResolverInterface::class),
        ]);
};
