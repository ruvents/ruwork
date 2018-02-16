<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\FilterBundle\FilterFactory;
use Ruwork\FilterBundle\FilterFactoryInterface;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->private();

    $services->set('ruwork_filter.factory', FilterFactory::class)
        ->args([
            '$formFactory' => ref('form.factory'),
        ]);

    $services->alias(FilterFactoryInterface::class, 'ruwork_filter.factory');
};
