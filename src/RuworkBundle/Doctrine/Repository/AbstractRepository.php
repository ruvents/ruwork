<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Utility\IdentifierFlattener;
use Happyr\DoctrineSpecification\Filter\Filter;
use Happyr\DoctrineSpecification\Query\QueryModifier;
use Happyr\DoctrineSpecification\Result\ResultModifier;

abstract class AbstractRepository extends ServiceEntityRepository
{
    protected $alias;
    private $identifierFlattener;

    public function __construct(ManagerRegistry $registry, ?string $entityClass = null, ?string $alias = null)
    {
        parent::__construct($registry, $entityClass ?? $this->getDefaultEntityClass());
        $this->alias = $alias ?? $this->getDefaultAlias();
        $this->identifierFlattener = new IdentifierFlattener(
            $this->_em->getUnitOfWork(),
            $this->_em->getMetadataFactory()
        );
    }

    /**
     * @throws EntityNotFoundException
     *
     * @return object
     */
    public function get($id, ?int $lockMode = null, ?int $lockVersion = null)
    {
        $entity = $this->find($id, $lockMode, $lockVersion);

        if (null === $entity) {
            if (!\is_array($id)) {
                $id = [$this->_class->identifier[0] => $id];
            }

            throw EntityNotFoundException::fromClassNameAndIdentifier(
                $this->getClassName(),
                $this->identifierFlattener->flattenIdentifier($this->_class, $id)
            );
        }

        return $entity;
    }

    /**
     * @param Filter|QueryModifier $specification
     */
    public function match($specification, ?ResultModifier $modifier = null)
    {
        return $this->createQueryFromSpec($specification, $modifier)->execute();
    }

    /**
     * @param Filter|QueryModifier $specification
     */
    public function matchOne($specification, ?ResultModifier $modifier = null)
    {
        return $this->createQueryFromSpec($specification, $modifier)->getOneOrNullResult();
    }

    /**
     * @param Filter|QueryModifier $specification
     */
    public function matchSingleScalar($specification, ?ResultModifier $modifier = null)
    {
        return $this->createQueryFromSpec($specification, $modifier)->getSingleScalarResult();
    }

    /**
     * @param Filter|QueryModifier $specification
     */
    public function createQueryBuilderFromSpec($specification): QueryBuilder
    {
        $qb = $this->createQueryBuilder($this->alias);
        $this->applySpec($qb, $specification);

        return $qb;
    }

    /**
     * @param Filter|QueryModifier $specification
     */
    public function createQueryFromSpec($specification, ?ResultModifier $modifier = null): Query
    {
        $query = $this->createQueryBuilderFromSpec($specification)->getQuery();

        if (null !== $modifier) {
            $modifier->modify($query);
        }

        return $query;
    }

    /**
     * @param Filter|QueryModifier $specification
     */
    public function applySpec(QueryBuilder $queryBuilder, $specification, ?string $alias = null): void
    {
        if (null === $alias) {
            $alias = $this->alias;
        }

        if ($specification instanceof QueryModifier) {
            $specification->modify($queryBuilder, $alias);
        }

        if ($specification instanceof Filter) {
            $filter = (string) $specification->getFilter($queryBuilder, $alias);

            if ($filter) {
                $queryBuilder->andWhere($filter);
            }
        }
    }

    protected function getDefaultEntityClass(): string
    {
        return preg_replace('/\\\Repository\\\(.+)Repository$/', '\\\Entity\\\\$1', static::class);
    }

    protected function getDefaultAlias(): string
    {
        preg_match('/\\\(\w+)$/', $this->getClassName(), $matches);

        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_$1', $matches[1]));
    }
}
