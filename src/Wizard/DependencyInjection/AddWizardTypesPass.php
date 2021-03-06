<?php

declare(strict_types=1);

namespace Ruwork\Wizard\DependencyInjection;

use Ruwork\Wizard\Exception\LogicException;
use Ruwork\Wizard\Wizard\Type\WizardTypeInterface;
use Ruwork\Wizard\Wizard\TypeResolver\WizardTypeResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class AddWizardTypesPass implements CompilerPassInterface
{
    private $serviceId;
    private $tag;

    public function __construct(
        string $serviceId = WizardTypeResolver::class,
        string $tag = 'ruwork_wizard.wizard_type'
    ) {
        $this->serviceId = $serviceId;
        $this->tag = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has($this->serviceId)) {
            return;
        }

        $tagged = $container->findTaggedServiceIds($this->tag, true);
        $references = [];

        foreach ($tagged as $id => $attributes) {
            $class = $container->getDefinition($id)->getClass();

            if (!is_subclass_of($class, WizardTypeInterface::class)) {
                throw new LogicException(sprintf(
                    'Service tagged with "%s" must implement "%s".',
                    $this->tag,
                    WizardTypeInterface::class
                ));
            }

            $references[$class] = new Reference($id);
        }

        $container
            ->findDefinition($this->serviceId)
            ->setArgument('$types', ServiceLocatorTagPass::register($container, $references));
    }
}
