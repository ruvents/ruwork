<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Handler\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Ruwork\Synchronizer\ContextInterface;
use Ruwork\Synchronizer\Handler\DeleterInterface;

final class EntityDeleter implements DeleterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($target, ContextInterface $context): void
    {
        $this->entityManager->remove($target);
    }
}
