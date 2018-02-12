<?php

declare(strict_types=1);

namespace Symfony\Component\Routing\Loader\Configurator;

use Ruwork\RoutingToolsBundle\Controller\RemoveTrailingSlashController;

return function (RoutingConfigurator $configurator): void {
    $configurator
        ->add('ruwork_routing_tools.remove_trailing_slash', '/{path}')
        ->controller(RemoveTrailingSlashController::class)
        ->requirements([
            'path' => '.*\/$',
        ]);
};
