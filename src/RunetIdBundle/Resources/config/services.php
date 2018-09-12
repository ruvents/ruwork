<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Http\Message\UriFactory;
use RunetId\Client\RunetIdClient;
use RunetId\Client\RunetIdClientFactory;
use Ruwork\RunetIdBundle\Basket\Factory\BasketFactory;
use Ruwork\RunetIdBundle\Basket\Factory\BasketFactoryInterface;
use Ruwork\RunetIdBundle\Basket\Form\FormErrorsMapper;
use Ruwork\RunetIdBundle\Basket\Form\FormErrorsMapperInterface;
use Ruwork\RunetIdBundle\Basket\Handler\ElementHandler;
use Ruwork\RunetIdBundle\Client\RunetIdClients;
use Ruwork\RunetIdBundle\Validator\UniqueEmailValidator;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    // Basket\Factory

    $services
        ->set(BasketFactory::class)
        ->args([
            '$clients' => ref(RunetIdClients::class),
        ]);

    $services->alias(BasketFactoryInterface::class, BasketFactory::class);

    // Basket\Form

    $services
        ->set(FormErrorsMapper::class)
        ->args([
            '$session' => ref('session'),
            '$accessor' => ref('property_accessor'),
        ]);

    $services->alias(FormErrorsMapperInterface::class, FormErrorsMapper::class);

    // Basket\Handler

    $services
        ->set(ElementHandler::class)
        ->tag('ruwork_runet_id.basket_handler');

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
            '$clients' => ref(RunetIdClients::class),
        ])
        ->tag('validator.constraint_validator');
};
