<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle\DependencyInjection\Compiler;

use Ruwork\WizardBundle\Type\TypeFacadeFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InjectWizardTypesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $serviceIds = $container->findTaggedServiceIds('ruwork_wizard.wizard_type', true);
        $types = [];

        foreach ($serviceIds as $id => $tags) {
            $types[$id] = new Reference($id);
        }

        $container->findDefinition(TypeFacadeFactory::class)
            ->setArgument('$wizardTypes', ServiceLocatorTagPass::register($container, $types));
    }
}
