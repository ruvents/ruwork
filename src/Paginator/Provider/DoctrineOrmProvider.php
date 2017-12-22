<?php

declare(strict_types=1);

namespace Ruwork\Paginator\Provider;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrineOrmProvider implements ProviderInterface
{
    private $paginator;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->paginator = new Paginator($queryBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): int
    {
        return $this->paginator->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(int $offset, int $limit): iterable
    {
        $this->paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $this->paginator;
    }
}
