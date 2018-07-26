<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Http\Message\UriFactory;
use RunetId\Client\RunetIdClient;
use RunetId\Client\RunetIdClientFactory;
use Ruwork\RunetIdBundle\Basket\BasketFactory;
use Ruwork\RunetIdBundle\Basket\BasketFactoryInterface;
use Ruwork\RunetIdBundle\Client\RunetIdClients;
use Ruwork\RunetIdBundle\Validator\UniqueEmailValidator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    // Basket

    $services
        ->set(BasketFactory::class)
        ->args([
            '$client' => ref(RunetIdClient::class),
            '$loaders' => tagged('ruwork_runet_id_basket.loader'),
            '$handlers' => tagged('ruwork_runet_id_basket.handler'),
        ]);

    $services->alias(BasketFactoryInterface::class, BasketFactory::class);

    // Client

    $services
        ->set(RunetIdClientFactory::class)
        ->args([
            '$httpClient' => ref(HttpClient::class)->nullOnInvalid(),
            '$uriFactory' => ref(UriFactory::class)->nullOnInvalid(),
            '$requestFactory' => ref(RequestFactory::class)->nullOnInvalid(),
            '$streamFactory' => ref(StreamFactory::class)->nullOnInvalid(),
        ]);

    $services
        ->set('ruwork_runet_id.client', RunetIdClient::class)
        ->abstract()
        ->factory([ref(RunetIdClientFactory::class), 'create']);

    $services->set(RunetIdClients::class);

    // Validator

    $services
        ->set(UniqueEmailValidator::class)
        ->args([
            '$container' => ref('ruwork_runet_id.client_container'),
        ])
        ->tag('validator.constraint_validator');
};
