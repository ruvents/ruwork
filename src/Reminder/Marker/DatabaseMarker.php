<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Marker;

use Doctrine\DBAL\Connection;

final class DatabaseMarker implements MarkerInterface
{
    private $connection;
    private $table;

    public function __construct(Connection $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * {@inheritdoc}
     */
    public function isMarked(string $id): bool
    {
        $count = $this->connection
            ->createQueryBuilder()
            ->select('count(marker)')
            ->from($this->table, 'marker')
            ->where('marker.id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetchColumn();

        return $count > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function mark(string $id): void
    {
        $this->connection
            ->createQueryBuilder()
            ->insert($this->table)
            ->setValue('id', ':id')
            ->setParameter('id', $id)
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function unmark(string $id): void
    {
        $this->connection
            ->createQueryBuilder()
            ->delete($this->table, 'marker')
            ->where('marker.id = :id')
            ->setParameter('id', $id)
            ->execute();
    }
}
