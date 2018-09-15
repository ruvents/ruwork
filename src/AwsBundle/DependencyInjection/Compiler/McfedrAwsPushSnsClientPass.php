<?php

declare(strict_types=1);

namespace Ruwork\AwsBundle\DependencyInjection\Compiler;

use Aws\Sdk;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class McfedrAwsPushSnsClientPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('mcfedr_aws_push.sns_client')) {
            return;
        }

        if (!$container->has(Sdk::class)) {
            return;
        }

        $container
            ->findDefinition('mcfedr_aws_push.sns_client')
            ->setFactory([new Reference(Sdk::class), 'createSns']);
    }
}
