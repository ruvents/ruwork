<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\Controller\ArgumentValueResolver;

use Ruwork\AdminBundle\Config\Model\Config;
use Ruwork\AdminBundle\Config\Model\EntityConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class EntityConfigResolver implements ArgumentValueResolverInterface
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return EntityConfig::class === $argument->getType()
            && $request->attributes->has('ruwork_admin_entity')
            && isset($this->config->entities[$request->attributes->get('ruwork_admin_entity')]);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->config->entities[$request->attributes->get('ruwork_admin_entity')];
    }
}
