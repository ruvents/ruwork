<?php
declare(strict_types=1);

namespace Ruwork\AdminBundle;

use Ruwork\AdminBundle\DependencyInjection\Compiler\ListFieldTypeContextProcessorsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ListFieldTypeContextProcessorsPass());
    }
}
