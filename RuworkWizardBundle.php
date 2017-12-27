<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle;

use Ruwork\WizardBundle\DependencyInjection\Compiler\InjectStepTypesPass;
use Ruwork\WizardBundle\DependencyInjection\Compiler\InjectWizardTypesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkWizardBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new InjectWizardTypesPass())
            ->addCompilerPass(new InjectStepTypesPass());
    }
}
