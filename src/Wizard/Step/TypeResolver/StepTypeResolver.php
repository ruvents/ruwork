<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Step\TypeResolver;

use Psr\Container\ContainerInterface;
use Ruwork\Wizard\Exception\UnexpectedValueException;
use Ruwork\Wizard\Step\Type\StepTypeInterface;

final class StepTypeResolver implements StepTypeResolverInterface
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
    public function resolve(string $typeName, array &$passedTypes = []): ResolvedStepTypeInterface
    {
        if (isset($this->resolvedTypes[$typeName])) {
            return $this->resolvedTypes[$typeName];
        }

        $type = $this->types->get($typeName);

        if (!$type instanceof StepTypeInterface) {
            throw UnexpectedValueException::fromValue($type, StepTypeInterface::class);
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

        return $this->resolvedTypes[$typeName] = new ResolvedStepType($typeName, $type, $all);
    }
}
