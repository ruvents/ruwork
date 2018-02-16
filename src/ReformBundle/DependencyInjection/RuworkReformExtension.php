<?php

declare(strict_types=1);

namespace Ruwork\ReformBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class RuworkReformExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        if (!$config['extensions']['novalidate']) {
            $container->removeDefinition('ruwork_reform.extension.form_novalidate');
        }

        if (!$config['extensions']['default_datetime_immutable']) {
            $container->removeDefinition('ruwork_reform.extension.date_time_default_dti');
            $container->removeDefinition('ruwork_reform.extension.date_default_dti');
            $container->removeDefinition('ruwork_reform.extension.time_default_dti');
        }
    }
}
