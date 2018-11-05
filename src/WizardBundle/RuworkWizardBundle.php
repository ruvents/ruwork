<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle;

use Ruwork\Wizard\DependencyInjection\AddStepTypesPass;
use Ruwork\Wizard\DependencyInjection\AddWizardTypesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkWizardBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new AddWizardTypesPass())
            ->addCompilerPass(new AddStepTypesPass());
    }
}
