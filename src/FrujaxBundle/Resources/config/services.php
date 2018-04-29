<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\FrujaxBundle\EventListener\FrujaxAnnotationListener;
use Ruwork\FrujaxBundle\EventListener\FrujaxRedirectListener;
use Ruwork\FrujaxBundle\EventListener\FrujaxResponseListener;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set('ruwork_frujax.redirect_listener', FrujaxRedirectListener::class)
        ->tag('kernel.event_subscriber');

    $services->set('ruwork_frujax.response_listener', FrujaxResponseListener::class)
        ->tag('kernel.event_subscriber');

    $services->set('ruwork_frujax.annotation_listener', FrujaxAnnotationListener::class)
        ->tag('kernel.event_subscriber');
};
