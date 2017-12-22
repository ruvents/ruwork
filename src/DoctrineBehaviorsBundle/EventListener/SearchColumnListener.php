<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\EventListener;

use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use Ruwork\DoctrineBehaviorsBundle\Helper\HashHelper;
use Ruwork\DoctrineBehaviorsBundle\Metadata\MetadataFactoryInterface;

class SearchColumnListener
{
    private $factory;

    public function __construct(MetadataFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function postGenerateSchemaTable(GenerateSchemaTableEventArgs $args): void
    {
        $class = $args->getClassMetadata()->name;
        $table = $args->getClassTable();

        foreach ($this->factory->getMetadata($class)->getSearchColumns() as $column) {
            $table->addColumn($column->name, $column->type)->setNotnull(false);

            if (null !== $index = $column->index) {
                $name = $index->name ?: HashHelper::generate('idx_', $column->name);
                $table->addIndex([$column->name], $name, [], $index->options);
            }
        }
    }
}
