<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle\DependencyInjection;

use Ruwork\WizardBundle\Type\StepTypeInterface;
use Ruwork\WizardBundle\Type\WizardTypeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class RuworkWizardExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $container->registerForAutoconfiguration(WizardTypeInterface::class)
            ->addTag('ruwork_wizard.wizard_type');

        $container->registerForAutoconfiguration(StepTypeInterface::class)
            ->addTag('ruwork_wizard.step_type');
    }
}
