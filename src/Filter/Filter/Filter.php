<?php

declare(strict_types=1);

namespace Ruwork\Filter\Filter;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class Filter implements FilterInterface
{
    private $accessor;
    private $children;

    public function __construct(PropertyAccessorInterface $accessor, array $children)
    {
        $this->accessor = $accessor;
        $this->children = $children;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(&$object, $data): void
    {
        foreach ($this->children as [$filter, $propertyPath]) {
            if (null === $propertyPath) {
                $filter($object, $data);
            } else {
                $childData = $this->accessor->getValue($data, $propertyPath);
                $filter($object, $childData);
            }
        }
    }
}
