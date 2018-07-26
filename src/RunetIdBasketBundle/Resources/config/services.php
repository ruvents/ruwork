<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use RunetId\Client\RunetIdClient;
use Ruwork\RunetIdBasketBundle\Basket\BasketFactory;
use Ruwork\RunetIdBasketBundle\Basket\BasketFactoryInterface;

return function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->private();

    $services
        ->set(BasketFactory::class)
        ->args([
            '$client' => ref(RunetIdClient::class),
            '$loaders' => tagged('ruwork_runet_id_basket.loader'),
            '$handlers' => tagged('ruwork_runet_id_basket.handler'),
        ]);

    $services->alias(BasketFactoryInterface::class, BasketFactory::class);
};
