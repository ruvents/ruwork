<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\FrujaxBundle\EventListener\FrujaxRedirectListener;
use Ruwork\FrujaxBundle\EventListener\FrujaxResponseListener;
use Ruwork\FrujaxBundle\EventListener\FrujaxTemplateListener;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(FrujaxRedirectListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FrujaxResponseListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FrujaxTemplateListener::class)
        ->arg('$twig', ref('twig'))
        ->tag('kernel.event_subscriber');
};
