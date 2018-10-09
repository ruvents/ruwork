<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Aws\Sdk;
use Aws\Ses\SesClient;
use Ruwork\AwsBundle\Client\AwsSdks;
use Ruwork\AwsBundle\HttpHandler\HttplugHandler;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    // Aws

    $services->set(Sdk::class);

    // Aws\Ses

    $services
        ->set(SesClient::class)
        ->factory([ref(Sdk::class), 'createSes']);

    // Sdks

    $services->set(AwsSdks::class);

    // HttpHandler

    $services
        ->set(HttplugHandler::class)
        ->args([
            '$client' => ref('httplug.async_client.default')->ignoreOnInvalid(),
        ]);
};
