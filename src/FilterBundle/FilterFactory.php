<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FilterFactory implements FilterFactoryInterface
{
    private $formFactory;
    private $types;

    public function __construct(FormFactoryInterface $formFactory, ContainerInterface $types)
    {
        $this->formFactory = $formFactory;
        $this->types = $types;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $type, array $options = []): FilterInterface
    {
        if (!$this->types->has($type)) {
            throw new \InvalidArgumentException(\sprintf('Filter type "%s" does not exist.', $type));
        }

        /** @var FilterTypeInterface $type */
        $type = $this->types->get($type);

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return new Filter($this->formFactory, $type, $options);
    }
}
