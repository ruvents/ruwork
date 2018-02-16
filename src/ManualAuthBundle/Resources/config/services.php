<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\ManualAuthBundle\ManualAuthScheduler;
use Ruwork\ManualAuthBundle\Security\ManualAuthListener;
use Ruwork\ManualAuthBundle\Security\ManualAuthProvider;
use Symfony\Component\HttpKernel\KernelEvents;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set('ruwork_manual_auth.provider')
        ->class(ManualAuthProvider::class);

    $services->set('ruwork_manual_auth.scheduler')
        ->class(ManualAuthScheduler::class);

    $services->set('ruwork_manual_auth.listener')
        ->class(ManualAuthListener::class)
        ->abstract()
        ->args([
            '$manager' => ref('security.authentication.manager'),
            '$scheduler' => ref('ruwork_manual_auth.scheduler'),
        ])
        ->tag('kernel.event_listener', [
            'event' => KernelEvents::RESPONSE,
            'priority' => -4000,
        ]);

    $services->alias(ManualAuthScheduler::class, 'ruwork_manual_auth.scheduler');
};
