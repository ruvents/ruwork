<?php

declare(strict_types=1);

namespace Ruwork\Filter\Builder;

use Ruwork\Filter\Factory\FilterFactoryInterface;
use Ruwork\Filter\Filter\Filter;
use Ruwork\Filter\Filter\FilterInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class FilterBuilder implements FilterBuilderInterface
{
    private $factory;
    private $accessor;
    private $options;
    private $children = [];
    private $sorted = false;

    public function __construct(FilterFactoryInterface $factory, PropertyAccessorInterface $accessor, array $options)
    {
        $this->factory = $factory;
        $this->accessor = $accessor;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption(string $name, $default = null)
    {
        return \array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function add(callable $filter, ?string $propertyPath = null, int $priority = 0): FilterBuilderInterface
    {
        $this->children[] = [$filter, $propertyPath, $priority];
        $this->sorted = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function embed(string $type, array $options = [], ?string $propertyPath = null, int $priority = 0): FilterBuilderInterface
    {
        $this->add(function (&$object, $data) use ($options, $type) {
            static $filter;

            if (!isset($filter)) {
                $filter = $this->factory->create($type, $options);
            }

            $filter->filter($object, $data);
        }, $propertyPath, $priority);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilter(): FilterInterface
    {
        $this->sortChildren();

        return new Filter($this->accessor, $this->children);
    }

    private function sortChildren(): void
    {
        if ($this->sorted) {
            return;
        }

        \usort($this->children, static function (array $a, array $b): int {
            return $a[2] <=> $b[2];
        });

        $this->sorted = true;
    }
}
