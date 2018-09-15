<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle;

use Ruwork\AwsBundle\DependencyInjection\Compiler\McfedrAwsPushSnsClientPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RuworkAwsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new McfedrAwsPushSnsClientPass());
    }
}
