<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\Factory;

use Ruwork\Wizard\Step\Builder\StepBuilder;
use Ruwork\Wizard\Step\StepInterface;
use Ruwork\Wizard\Step\TypeResolver\StepTypeResolverInterface;

final class StepFactory implements StepFactoryInterface
{
    private $typeResolver;

    public function __construct(StepTypeResolverInterface $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $name, string $type, array $options = []): StepInterface
    {
        $type = $this->typeResolver->resolve($type);
        $options = $type->getOptionsResolver()->resolve($options);
        $builder = new StepBuilder($name, $options);
        $type->configureStep($builder, $options);

        return $builder->build();
    }
}
