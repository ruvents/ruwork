<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\TypeResolver;

use Psr\Container\ContainerInterface;
use Ruwork\ObjectStore\Exception\UnexpectedValueException;
use Ruwork\ObjectStore\Type\StoreTypeInterface;

final class StoreTypeResolver implements StoreTypeResolverInterface
{
    private $types;
    private $resolvedTypes;

    public function __construct(ContainerInterface $types)
    {
        $this->types = $types;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(string $typeName): ResolvedStoreTypeInterface
    {
        if (isset($this->resolvedTypes[$typeName])) {
            return $this->resolvedTypes[$typeName];
        }

        $type = $this->types->get($typeName);

        if (!$type instanceof StoreTypeInterface) {
            throw UnexpectedValueException::createForValue($type, StoreTypeInterface::class);
        }

        $all = [];

        foreach ($type::getRequiredTypes() as $childName) {
            if (isset($all[$childName])) {
                continue;
            }

            $resolvedChild = $this->resolve($childName);

            foreach ($resolvedChild->getRequiredTypes() as $child2Name => $child2) {
                if (isset($all[$child2Name])) {
                    continue;
                }

                $all[$child2Name] = $child2;
            }

            $all[$childName] = $resolvedChild->getType();
        }

        return $this->resolvedTypes[$typeName] = new ResolvedStoreType($typeName, $type, $all);
    }
}
