<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle\DependencyInjection;

use Ruwork\Wizard\Step\Type\StepTypeInterface;
use Ruwork\Wizard\Wizard\Type\WizardTypeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class RuworkWizardExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new PhpFileLoader($container, $locator);
        $loader->load('services.php');

        $container->registerForAutoconfiguration(WizardTypeInterface::class)
            ->setPrivate(true)
            ->addTag('ruwork_wizard.wizard_type');

        $container->registerForAutoconfiguration(StepTypeInterface::class)
            ->setPrivate(true)
            ->addTag('ruwork_wizard.step_type');
    }
}
