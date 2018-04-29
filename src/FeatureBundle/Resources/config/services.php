<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\FeatureBundle\EventListener\FeatureAnnotationListener;
use Ruwork\FeatureBundle\FeatureChecker;
use Ruwork\FeatureBundle\FeatureCheckerInterface;
use Ruwork\FeatureBundle\Twig\FeatureExtension;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->private();

    $services->set('ruwork_feature.checker', FeatureChecker::class);

    $services->alias(FeatureCheckerInterface::class, 'ruwork_feature.checker');

    $services->set('ruwork_feature.twig_extension', FeatureExtension::class)
        ->arg('$checker', ref('ruwork_feature.checker'))
        ->tag('twig.extension');

    $services->set('ruwork_feature.annotation_listener', FeatureAnnotationListener::class)
        ->arg('$checker', ref('ruwork_feature.checker'))
        ->tag('kernel.event_subscriber');
};
