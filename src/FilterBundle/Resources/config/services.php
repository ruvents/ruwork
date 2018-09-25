<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\Filter\Factory\FilterFactory;
use Ruwork\Filter\Factory\FilterFactoryInterface;

return function (ContainerConfigurator $container): void {
    $services = $container
        ->services()
        ->defaults()
        ->private();

    // Factory

    $services
        ->set(FilterFactory::class)
        ->args([
            '$accessor' => ref('property_accessor'),
        ]);

    $services
        ->alias(FilterFactoryInterface::class, FilterFactory::class)
        ->public();
};
