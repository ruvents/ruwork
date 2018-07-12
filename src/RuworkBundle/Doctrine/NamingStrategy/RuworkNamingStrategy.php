<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Doctrine\NamingStrategy;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Mapping\NamingStrategy;

final class RuworkNamingStrategy implements NamingStrategy
{
    /**
     * {@inheritdoc}
     */
    public function classToTableName($className)
    {
        $className = \preg_replace('/(^.*\\\Entity\\\|\\\)/', '', $className);

        return Inflector::tableize($className);
    }

    /**
     * {@inheritdoc}
     */
    public function propertyToColumnName($propertyName, $className = null)
    {
        return Inflector::tableize($propertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function embeddedFieldToColumnName(
        $propertyName,
        $embeddedColumnName,
        $className = null,
        $embeddedClassName = null
    ) {
        return $this->propertyToColumnName($propertyName).'_'.$embeddedColumnName;
    }

    /**
     * {@inheritdoc}
     */
    public function referenceColumnName()
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function joinColumnName($propertyName, $className = null)
    {
        return $this->propertyToColumnName($propertyName).'_'.$this->referenceColumnName();
    }

    /**
     * {@inheritdoc}
     */
    public function joinTableName($sourceEntity, $targetEntity, $propertyName = null)
    {
        return $this->classToTableName($sourceEntity).'_link_'.$this->classToShortTableName($targetEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function joinKeyColumnName($entityName, $referencedColumnName = null)
    {
        return $this->classToShortTableName($entityName).'_'.($referencedColumnName ?: $this->referenceColumnName());
    }

    private function classToShortTableName(string $className): string
    {
        if (false !== $rpos = \strrpos($className, '\\')) {
            $className = \substr($className, $rpos + 1);
        }

        return Inflector::tableize($className);
    }
}
