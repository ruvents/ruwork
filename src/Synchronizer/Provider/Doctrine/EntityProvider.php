<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Provider\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ruwork\Synchronizer\Provider\ByIdProviderInterface;
use Ruwork\Synchronizer\Provider\ProviderInterface;

final class EntityProvider implements ProviderInterface, ByIdProviderInterface
{
    private const ALIAS = 'entity';

    private $entityManager;
    private $class;

    public function __construct(EntityManagerInterface $entityManager, string $class)
    {
        $this->entityManager = $entityManager;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): iterable
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager
            ->getRepository($this->class);

        $iterator = $repository
            ->createQueryBuilder(self::ALIAS)
            ->getQuery()
            ->iterate();

        foreach ($iterator as $item) {
            yield $item[0];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOneById($id)
    {
        return $this->entityManager->find($this->class, $id);
    }
}
