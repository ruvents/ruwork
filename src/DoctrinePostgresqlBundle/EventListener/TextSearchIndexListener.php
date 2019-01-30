<?php

declare(strict_types=1);

namespace Ruwork\DoctrinePostgresqlBundle\EventListener;

use Doctrine\DBAL\Event\SchemaAlterTableEventArgs;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;

class TextSearchIndexListener extends AbstractListener
{
    private const OPTION = 'pgsql_txt_srch';

    public function onSchemaAlterTable(SchemaAlterTableEventArgs $args): void
    {
        $platform = $args->getPlatform();
        $this->checkPostgresqlPlatform($platform);

        $diff = $args->getTableDiff();
        $table = $diff->fromTable;

        foreach ($diff->renamedIndexes as $oldName => $newIndex) {
            $oldIndex = $table->getIndex($oldName);

            if ($this->indexDiff($oldIndex, $newIndex)) {
                unset($diff->renamedIndexes[$oldName]);
                $diff->removedIndexes[] = $oldIndex;
                $diff->addedIndexes[] = $newIndex;
            }
        }

        foreach ($diff->addedIndexes as $key => $addedIndex) {
            if ($addedIndex->hasOption(self::OPTION)) {
                $type = $addedIndex->getOption(self::OPTION);
                $args->addSql($this->createIndexSQL($platform, $table, $addedIndex, $type));
                unset($diff->addedIndexes[$key]);
            }
        }

        foreach ($diff->changedIndexes as $key => $changedIndex) {
            if ($changedIndex->hasOption(self::OPTION)) {
                $type = $changedIndex->getOption(self::OPTION);
                $args->addSql($platform->getDropIndexSQL($changedIndex, $table));
                $args->addSql($this->createIndexSQL($platform, $table, $changedIndex, $type));
                unset($diff->changedIndexes[$key]);
            }
        }
    }

    private function createIndexSQL(AbstractPlatform $platform, Table $table, Index $index, string $type): string
    {
        $columns = $index->getQuotedColumns($platform);
        $type = strtoupper($type);

        if (1 !== \count($columns)) {
            throw new \InvalidArgumentException(sprintf('Invalid %s index definition. Exactly 1 column must be assigned.', $type));
        }

        $table = $table->getQuotedName($platform);
        $name = $index->getQuotedName($platform);
        $list = $platform->getIndexFieldDeclarationListSQL($columns);

        return sprintf('CREATE'.' INDEX %s ON %s USING %s(%s)', $name, $table, $type, $list);
    }

    private function indexDiff(Index $a, Index $b): bool
    {
        if ($a->hasOption(self::OPTION)) {
            if (!$b->hasOption(self::OPTION)) {
                return true;
            }

            return $a->getOption(self::OPTION) !== $b->getOption(self::OPTION);
        }

        return $b->hasOption(self::OPTION);
    }
}
