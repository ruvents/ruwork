<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Factory;

use Ruwork\ObjectStore\Configurator\StoreBuilder;
use Ruwork\ObjectStore\StoreInterface;
use Ruwork\ObjectStore\TypeResolver\StoreTypeResolverInterface;

final class StoreFactory implements StoreFactoryInterface
{
    private $typeResolver;

    public function __construct(StoreTypeResolverInterface $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $type, array $options = []): StoreInterface
    {
        $resolvedType = $this->typeResolver->resolve($type);
        $options = $resolvedType->getOptionsResolver()->resolve($options);
        $builder = new StoreBuilder();
        $resolvedType->configureStore($builder, $options);

        return $builder->build();
    }
}
