<?php

declare(strict_types=1);

namespace Ruwork\ReminderBundle\DependencyInjection;

use Ruwork\Reminder\Marker\DatabaseMarker;
use Ruwork\Reminder\Marker\MarkerInterface;
use Ruwork\Reminder\Provider\ProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class RuworkReminderExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new PhpFileLoader($container, $locator);
        $loader->load('services.php');

        $markerId = $this->registerMarker($container, $config['marker']);
        $container->setAlias(MarkerInterface::class, $markerId);

        $container
            ->registerForAutoconfiguration(ProviderInterface::class)
            ->addTag('ruwork_reminder.provider');
    }

    private function registerMarker(ContainerBuilder $container, array $configs): string
    {
        $config = reset($configs);
        $type = key($configs);
        $id = 'ruwork_reminder.marker';

        switch ($type) {
            case 'database':
                $definition = (new ChildDefinition(DatabaseMarker::class.'.abstract'))
                    ->setArgument('$table', $config['table'])
                    ->setArgument('$connection', new Reference("doctrine.dbal.{$config['connection']}_connection"));
                $container->setDefinition($id, $definition);

                return $id;

            case 'service':
                return $config;
        }

        throw new \InvalidArgumentException(sprintf('Unknown marker type "%s".', $type));
    }
}
