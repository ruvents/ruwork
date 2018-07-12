<?php

declare(strict_types=1);

namespace Ruwork\DoctrinePostgresqlBundle\EventListener;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\PostgreSqlSchemaManager;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class FixDefaultSchemaListener extends AbstractListener
{
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $connection = $args->getEntityManager()->getConnection();

        $this->checkPostgresqlPlatform($connection->getDatabasePlatform());

        foreach ($this->getExistingNamespaces($connection) as $namespace) {
            if (!$args->getSchema()->hasNamespace($namespace)) {
                $args->getSchema()->createNamespace($namespace);
            }
        }
    }

    public function getExistingNamespaces(Connection $connection): array
    {
        $schemaManager = $connection->getSchemaManager();

        if ($schemaManager instanceof PostgreSqlSchemaManager) {
            return $schemaManager->getExistingSchemaSearchPaths();
        }

        $namespaceRows = $connection->fetchAll('SELECT'." nspname FROM pg_namespace WHERE nspname !~ '^pg_.*' AND nspname != 'information_schema'");
        $namespaces = \array_column($namespaceRows, 'nspname');

        return \array_intersect($namespaces, $schemaManager->getSchemaSearchPaths());
    }
}
