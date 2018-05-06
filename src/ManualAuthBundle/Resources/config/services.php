<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\ManualAuthBundle\ManualAuthListener;
use Ruwork\ManualAuthBundle\ManualAuthProvider;
use Ruwork\ManualAuthBundle\ManualAuthTokens;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(ManualAuthTokens::class);

    $services->set('ruwork_manual_auth.provider')
        ->class(ManualAuthProvider::class);

    $services->set('ruwork_manual_auth.listener')
        ->class(ManualAuthListener::class)
        ->abstract()
        ->args([
            '$manager' => ref('security.authentication.manager'),
            '$tokens' => ref(ManualAuthTokens::class),
        ]);
};
