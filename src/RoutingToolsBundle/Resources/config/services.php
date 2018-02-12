<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\RouteOptionalPrefix\LoaderDecorator;
use Ruwork\RouteOptionalPrefix\RouterDecorator;
use Ruwork\RoutingToolsBundle\RedirectFactory\RedirectFactory;
use Ruwork\RoutingToolsBundle\RedirectFactory\RedirectFactoryInterface;
use Ruwork\RoutingToolsBundle\Twig\RoutingHelpersExtension;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set(RoutingHelpersExtension::class)
        ->private()
        ->args([
            '$requestStack' => ref('request_stack'),
        ])
        ->tag('twig.extension');

    $services->set(LoaderDecorator::class)
        ->private()
        ->decorate('routing.loader')
        ->args([
            '$loader' => ref(LoaderDecorator::class.'.inner'),
        ]);

    $services->set(RouterDecorator::class)
        ->private()
        ->decorate('router')
        ->args([
            '$router' => ref(RouterDecorator::class.'.inner'),
        ]);

    $services->set('ruwork_routing_tools.redirect_factory', RedirectFactory::class)
        ->private()
        ->args([
            '$urlGenerator' => ref('router'),
        ]);

    $services->alias(RedirectFactoryInterface::class, 'ruwork_routing_tools.redirect_factory')
        ->private();
};
