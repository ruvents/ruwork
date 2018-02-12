<?php

declare(strict_types=1);

namespace Symfony\Component\Routing\Loader\Configurator;

return function (RoutingConfigurator $configurator): void {
    $configurator
        ->add('ruwork_routing_tools.remove_trailing_slash', '/{path}')
        ->controller('ruwork_routing_tools.controller.remove_trailing_slash')
        ->requirements([
            'path' => '.*\/$',
        ]);
};
