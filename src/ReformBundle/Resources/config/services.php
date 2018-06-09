<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\Reform\Extension\CheckboxTypeFalseValueExtension;
use Ruwork\Reform\Extension\DateTimeTypeDefaultDTIExtension;
use Ruwork\Reform\Extension\DateTypeDefaultDTIExtension;
use Ruwork\Reform\Extension\FormTypeNovalidateExtension;
use Ruwork\Reform\Extension\TimeTypeDefaultDTIExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->private();

    $services->set('ruwork_reform.extension.checkbox_false_value')
        ->class(CheckboxTypeFalseValueExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => CheckboxType::class,
            'priority' => 512,
        ]);

    $services->set('ruwork_reform.extension.form_novalidate')
        ->class(FormTypeNovalidateExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => FormType::class,
            'priority' => 512,
        ]);

    $services->set('ruwork_reform.extension.date_time_default_dti')
        ->class(DateTimeTypeDefaultDTIExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => DateTimeType::class,
            'priority' => 512,
        ]);

    $services->set('ruwork_reform.extension.date_default_dti')
        ->class(DateTypeDefaultDTIExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => DateType::class,
            'priority' => 512,
        ]);

    $services->set('ruwork_reform.extension.time_default_dti')
        ->class(TimeTypeDefaultDTIExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => TimeType::class,
            'priority' => 512,
        ]);
};
