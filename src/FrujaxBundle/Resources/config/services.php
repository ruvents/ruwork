<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\FrujaxBundle\EventListener\FrujaxAnnotationListener;
use Ruwork\FrujaxBundle\EventListener\FrujaxRedirectListener;
use Ruwork\FrujaxBundle\EventListener\FrujaxResponseListener;
use Ruwork\FrujaxBundle\Twig\FrujaxBlockAwareRenderer;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(FrujaxAnnotationListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FrujaxRedirectListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FrujaxResponseListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FrujaxBlockAwareRenderer::class)
        ->args([
            '$twig' => ref('twig'),
            '$requestStack' => ref('request_stack'),
        ]);
};
