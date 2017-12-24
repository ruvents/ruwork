<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\LocalePrefixBundle\Routing\LoaderDecorator;
use Ruwork\LocalePrefixBundle\Routing\Router;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('ruwork_locale_prefix.loader_decorator', LoaderDecorator::class)
        ->private()
        ->decorate('routing.loader')
        ->args([
            '$loader' => ref('ruwork_locale_prefix.loader_decorator.inner'),
        ]);

    $services->set('ruwork_locale_prefix.default_router', Router::class)
        ->private()
        ->parent('router.default')
        ->call('setRequestStack', [ref('request_stack')]);
};
