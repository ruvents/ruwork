<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\RouteOptionalPrefix\LoaderDecorator;
use Ruwork\RouteOptionalPrefix\RouterDecorator;
use Ruwork\RoutingToolsBundle\Controller\RemoveTrailingSlashController;
use Ruwork\RoutingToolsBundle\RedirectFactory;
use Ruwork\RoutingToolsBundle\Twig\RoutingHelpersExtension;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->private();

    $services->set(RoutingHelpersExtension::class)
        ->args([
            '$requestStack' => ref('request_stack'),
        ])
        ->tag('twig.extension');

    $services->set(LoaderDecorator::class)
        ->decorate('routing.loader')
        ->args([
            '$loader' => ref(LoaderDecorator::class.'.inner'),
        ]);

    $services->set(RouterDecorator::class)
        ->decorate('router')
        ->args([
            '$router' => ref(RouterDecorator::class.'.inner'),
        ]);

    $services->set('ruwork_routing_tools.redirect_factory', RedirectFactory::class)
        ->args([
            '$urlGenerator' => ref('router'),
        ]);

    $services->alias('redirect_factory', 'ruwork_routing_tools.redirect_factory');

    $services->alias(RedirectFactory::class, 'redirect_factory');

    $services->set('ruwork_routing_tools.controller.remove_trailing_slash')
        ->class(RemoveTrailingSlashController::class)
        ->public()
        ->args([
            '$redirectFactory' => ref('redirect_factory'),
        ]);
};
