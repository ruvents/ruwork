<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\FrujaxBundle\EventListener\FrujaxRedirectListener;
use Ruwork\FrujaxBundle\EventListener\FrujaxPartListener;
use Ruwork\FrujaxBundle\Form\TypeExtension\FrujaxFormTypeExtension;
use Ruwork\FrujaxBundle\Twig\Extension\FrujaxExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services->set(FrujaxRedirectListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FrujaxPartListener::class)
        ->tag('kernel.event_subscriber')
        ->tag('twig.runtime');

    $services->set(FrujaxFormTypeExtension::class)
        ->args([
            '$requestStack' => ref('request_stack'),
        ])
        ->tag('form.type_extension', [
            'extended_type' => FormType::class,
        ]);

    $services->set(FrujaxExtension::class)
        ->tag('twig.extension');
};
