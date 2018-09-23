<?php

declare(strict_types=1);

namespace Ruwork\Filter\Factory;

use Psr\Container\ContainerInterface;
use Ruwork\Filter\Builder\FilterBuilder;
use Ruwork\Filter\Builder\FilterBuilderInterface;
use Ruwork\Filter\Filter\FilterInterface;
use Ruwork\Filter\Type\FilterTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class FilterFactory implements FilterFactoryInterface
{
    private $types;
    private $accessor;

    public function __construct(ContainerInterface $types, PropertyAccessorInterface $accessor)
    {
        $this->types = $types;
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function createBuilder(?string $typeName = null, array $options = []): FilterBuilderInterface
    {
        if (null !== $typeName) {
            /** @var FilterTypeInterface $type */
            $type = $this->types->get($typeName);

            $resolver = new OptionsResolver();
            $type->configureOptions($resolver);
            $options = $resolver->resolve($options);
        }

        $builder = new FilterBuilder($this, $this->accessor, $options);

        if (isset($type)) {
            $type->build($builder, $options);
        }

        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $type, array $options = []): FilterInterface
    {
        return $this->createBuilder($type, $options)->getFilter();
    }
}
