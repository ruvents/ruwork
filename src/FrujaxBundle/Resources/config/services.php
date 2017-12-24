<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\FrujaxBundle\EventListener\FrujaxListener;
use Symfony\Component\HttpKernel\KernelEvents;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(FrujaxListener::class)
        ->tag('kernel.event_listener', ['event' => KernelEvents::RESPONSE]);
};
