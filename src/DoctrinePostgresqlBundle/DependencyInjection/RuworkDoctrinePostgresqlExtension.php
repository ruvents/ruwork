<?php

declare(strict_types=1);

namespace Ruwork\DoctrinePostgresqlBundle\DependencyInjection;

use Doctrine\DBAL\Events as DBALEvents;
use Doctrine\ORM\Tools\ToolEvents;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class RuworkDoctrinePostgresqlExtension extends ConfigurableExtension
{
    public const PREFIX = 'ruwork_doctrine_postgresql.';
    public const LISTENER = self::PREFIX.'listener.';

    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        if (isset($config['profiles']['*'])) {
            foreach ($this->getListeners($config['profiles']['*']) as $listener) {
                $container->setDefinition($listener->getParent().'.any', $listener);
            }
        } else {
            foreach ($config['profiles'] as $connection => $connectionConfig) {
                foreach ($this->getListeners($connectionConfig, $connection) as $listener) {
                    $container->setDefinition($listener->getParent().'.'.$connection, $listener);
                }
            }
        }
    }

    /**
     * @return ChildDefinition[]|\Generator
     */
    private function getListeners(array $config, string $connection = null): \Generator
    {
        $attr = function (string $event) use ($connection): array {
            return [
                'event' => $event,
                'connection' => $connection,
                'lazy' => true,
            ];
        };

        foreach ($config as $behavior => $behaviorConfig) {
            if (!$behaviorConfig['enabled']) {
                continue;
            }

            $definition = new ChildDefinition(self::LISTENER.$behavior);

            switch ($behavior) {
                case 'text_search_index':
                    $definition
                        ->addTag('doctrine.event_listener', $attr(DBALEvents::onSchemaAlterTable));

                    break;

                case 'fix_default_schema':
                    $definition
                        ->addTag('doctrine.event_listener', $attr(ToolEvents::postGenerateSchema));

                    break;
            }

            yield $definition;
        }
    }
}
