<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\DependencyInjection;

use Doctrine\ORM\Events as ORMEvents;
use Doctrine\ORM\Tools\ToolEvents;
use Ruwork\DoctrineBehaviorsBundle\Metadata\LazyLoadingMetadataFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\HttpKernel\KernelEvents;

class RuworkDoctrineBehaviorsExtension extends ConfigurableExtension
{
    const PREFIX = 'ruwork_doctrine_behaviors.';
    const LISTENER = self::PREFIX.'listener.';

    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        if ($container->getParameter('kernel.debug')) {
            $container->findDefinition(LazyLoadingMetadataFactory::class)
                ->setArgument('$cache', null);
        }

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
     * @return \Generator|ChildDefinition[]
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
                case 'search_column':
                    $definition
                        ->addTag('doctrine.event_listener', $attr(ToolEvents::postGenerateSchemaTable));

                    break;

                case 'author':
                    $definition
                        ->addTag('doctrine.event_listener', $attr(ORMEvents::prePersist));

                    break;

                case 'multilingual':
                    $definition
                        ->setArgument('$defaultLocale', $behaviorConfig['default_locale'])
                        ->addTag('kernel.event_listener', ['event' => KernelEvents::REQUEST])
                        ->addTag('doctrine.event_listener', $attr(ORMEvents::prePersist))
                        ->addTag('doctrine.event_listener', $attr(ORMEvents::postLoad));

                    break;

                case 'persist_timestamp':
                    $definition
                        ->addTag('doctrine.event_listener', $attr(ORMEvents::prePersist));

                    break;

                case 'update_timestamp':
                    $definition
                        ->addTag('doctrine.event_listener', $attr(ORMEvents::preUpdate));

                    break;
            }

            if (isset($behaviorConfig['strategy'])) {
                $definition->setArgument('$strategy', new Reference($behaviorConfig['strategy']));
            }

            if ($behaviorConfig['default_mapping']['enabled'] ?? false) {
                $variant = $behaviorConfig['default_mapping']['enabled_variant'];
                $mapping = $behaviorConfig['default_mapping'][$variant];

                $definition
                    ->addMethodCall('setDefaultMapping', [$variant, $mapping])
                    ->addTag('doctrine.event_listener', $attr(ORMEvents::loadClassMetadata));
            }

            yield $definition;
        }
    }
}
