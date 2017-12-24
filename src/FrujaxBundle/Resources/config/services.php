<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\FrujaxBundle\EventListener\FrujaxListener;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(FrujaxListener::class)
        ->tag('kernel.event_subscriber');
};
