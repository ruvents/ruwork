<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Search;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Ruwork\DoctrineBehaviorsBundle\Mapping\SearchColumn;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SearchManager
{
    private $doctrine;
    private $factory;
    private $accessor;

    public function __construct(ManagerRegistry $doctrine, MetadataFactoryInterface $factory, PropertyAccessor $accessor)
    {
        $this->doctrine = $doctrine;
        $this->factory = $factory;
        $this->accessor = $accessor;
    }

    public function updateIndex($entity, string $name = null): void
    {
        $class = get_class($entity);
        $manager = $this->doctrine->getManagerForClass($class);

        if (!$manager instanceof EntityManagerInterface) {
            throw new \InvalidArgumentException(sprintf('"%s" is not an entity', $class));
        }

        $mapping = $this->getMapping($entity, $name);

        $generator = $this->getIndexGenerator($entity, $mapping->propertyPaths);
        $index = $this->implodeRecursive($generator);

        $connection = $manager->getConnection();
        $metadata = $manager->getClassMetadata($class);
        $table = $connection->quoteIdentifier($metadata->getTableName());
        $id = $metadata->getIdentifierValues($entity);

        $connection->update($table, [$mapping->name => $index], $id);
    }

    private function getMapping($entity, string $name = null): SearchColumn
    {
        $mappings = $this->factory
            ->getMetadata($class = get_class($entity))
            ->getSearchColumns();

        if (null === $name) {
            $mapping = reset($mappings);

            if (!$mapping) {
                throw new \OutOfBoundsException(sprintf('No @SearchColumn mappings were found in "%s".', $class));
            }

            return $mapping;
        }

        if (!isset($mappings[$name])) {
            throw new \OutOfBoundsException(sprintf('@SearchColumn "%s" was not found in "%s".', $name, $class));
        }

        return $mappings[$name];
    }

    private function getIndexGenerator($entity, array $propertyPaths): \Generator
    {
        foreach ($propertyPaths as $propertyPath) {
            yield $this->accessor->getValue($entity, $propertyPath);
        }
    }

    private function implodeRecursive($value): string
    {
        if (is_iterable($value)) {
            $string = '';

            foreach ($value as $item) {
                $string .= ' '.$this->implodeRecursive($item);
            }

            return $string;
        }

        return (string) $value;
    }
}
