<?php

declare(strict_types=1);

namespace Symfony\Component\Routing\Loader\Configurator;

use Ruwork\AdminBundle\Controller;

return function (RoutingConfigurator $configurator): void {
    $configurator
        ->add('ruwork_admin', '')
        ->controller(Controller\IndexController::class);

    $configurator
        ->add('ruwork_admin_list', '/{ruwork_admin_entity}')
        ->requirements([
            'ruwork_admin_entity' => '%ruwork_admin.routing.list.entities_requirement%',
        ])
        ->controller(Controller\ListController::class);

    $configurator
        ->add('ruwork_admin_create', '/{ruwork_admin_entity}/create')
        ->requirements([
            'ruwork_admin_entity' => '%ruwork_admin.routing.create.entities_requirement%',
        ])
        ->controller(Controller\CreateController::class);

    $configurator
        ->add('ruwork_admin_edit', '/{ruwork_admin_entity}/edit/{id}')
        ->requirements([
            'ruwork_admin_entity' => '%ruwork_admin.routing.edit.entities_requirement%',
            'id' => '[\w-]+',
        ])
        ->controller(Controller\EditController::class);

    $configurator
        ->add('ruwork_admin_delete', '/{ruwork_admin_entity}/delete/{id}')
        ->requirements([
            'ruwork_admin_entity' => '%ruwork_admin.routing.delete.entities_requirement%',
            'id' => '[\w-]+',
        ])
        ->controller(Controller\DeleteController::class);
};
