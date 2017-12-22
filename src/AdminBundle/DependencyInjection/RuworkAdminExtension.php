<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle\DependencyInjection;

use Ruwork\AdminBundle\Config\ConfigManager;
use Ruwork\AdminBundle\Config\Pass\PassInterface;
use Ruwork\AdminBundle\ListField\TypeContextProcessor\TypeContextProcessorInterface;
use Ruwork\AdminBundle\ListField\TypeGuesser\TypeGuesserInterface;
use Ruwork\AdminBundle\Twig\ListExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class RuworkAdminExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $listableEntities = [];
        $creatableEntities = [];
        $editableEntities = [];
        $deletableEntities = [];

        foreach ($config['entities'] as $name => $entityConfig) {
            if ($entityConfig['list']['enabled']) {
                $listableEntities[] = $name;
            }
            if ($entityConfig['create']['enabled']) {
                $creatableEntities[] = $name;
            }
            if ($entityConfig['edit']['enabled']) {
                $editableEntities[] = $name;
            }
            if ($entityConfig['delete']['enabled']) {
                $deletableEntities[] = $name;
            }
        }

        $container->setParameter('ruwork_admin.routing.entities_requirement',
            $this->createRouteRequirement(array_keys($config['entities'])));

        $container->setParameter('ruwork_admin.routing.list.entities_requirement',
            $this->createRouteRequirement($listableEntities));

        $container->setParameter('ruwork_admin.routing.create.entities_requirement',
            $this->createRouteRequirement($creatableEntities));

        $container->setParameter('ruwork_admin.routing.edit.entities_requirement',
            $this->createRouteRequirement($editableEntities));

        $container->setParameter('ruwork_admin.routing.delete.entities_requirement',
            $this->createRouteRequirement($deletableEntities));

        $container->findDefinition(ConfigManager::class)
            ->setArgument('$data', $config)
            ->setArgument('$debug', $config['debug']);

        $container->registerForAutoconfiguration(PassInterface::class)
            ->addTag('ruwork_admin.config_pass');

        $container->registerForAutoconfiguration(TypeGuesserInterface::class)
            ->addTag('ruwork_admin.list_field_type_guesser');

        $container->registerForAutoconfiguration(TypeContextProcessorInterface::class)
            ->addTag('ruwork_admin.list_field_type_context_processor');

        $container->findDefinition(ListExtension::class)
            ->setArgument('$typesTemplate', $config['list']['types_template']);
    }

    private function createRouteRequirement(array $entityNames): string
    {
        return implode('|', $entityNames) ?: 'no-entities';
    }
}
