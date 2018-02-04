<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Http\Message\UriFactory;
use RunetId\Client\RunetIdClient;
use RunetId\Client\RunetIdClientFactory;
use Ruwork\RunetIdBundle\Validator\UniqueEmailValidator;
use Symfony\Component\DependencyInjection\ServiceLocator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services
        ->set('ruwork_runet_id.client_container', ServiceLocator::class)
        ->tag('container.service_locator');

    $services
        ->set('ruwork_runet_id.client_factory', RunetIdClientFactory::class)
        ->args([
            '$httpClient' => ref(HttpClient::class)->nullOnInvalid(),
            '$uriFactory' => ref(UriFactory::class)->nullOnInvalid(),
            '$requestFactory' => ref(RequestFactory::class)->nullOnInvalid(),
            '$streamFactory' => ref(StreamFactory::class)->nullOnInvalid(),
        ]);

    $services
        ->set('ruwork_runet_id.client', RunetIdClient::class)
        ->abstract()
        ->factory([
            ref('ruwork_runet_id.client_factory'),
            'create',
        ]);

    $services
        ->set('ruwork_runet_id.validator.unique_email', UniqueEmailValidator::class)
        ->arg('$container', ref('ruwork_runet_id.client_container'))
        ->tag('validator.constraint_validator');
};
