<?php
declare(strict_types=1);

namespace Ruwork\UploadBundle;

use Ruwork\UploadBundle\DependencyInjection\Compiler\ReplaceTwigAssetExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkUploadBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ReplaceTwigAssetExtensionPass());
    }
}
