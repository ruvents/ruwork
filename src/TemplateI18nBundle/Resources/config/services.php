<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

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
            '$strategy' => ref('ruwork_template_i18n.naming_strategy'),
            '$twig' => ref('twig'),
        ]);

    $services->alias(LocalizedTemplateResolverInterface::class, 'ruwork_template_i18n.resolver');

    $services->set('ruwork_template_i18n.annotation_listener')
        ->class(TemplateAnnotationListener::class)
        ->args([
            '$resolver' => ref('ruwork_template_i18n.resolver'),
        ])
        ->tag('kernel.event_subscriber');
};
