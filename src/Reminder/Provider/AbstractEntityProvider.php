<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Provider;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Ruwork\Reminder\Item\EntityItem;

abstract class AbstractEntityProvider implements ProviderInterface
{
    protected $useKeyAsItemId = false;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    final public function getItems(\DateTimeImmutable $time): iterable
    {
        $class = $this->getClass();
        $manager = $this->managerRegistry->getManagerForClass($class);
        $repository = $manager->getRepository($class);

        foreach ($this->getEntityIds($repository, $time) as $key => $id) {
            $itemId = $this->useKeyAsItemId ? $key : null;

            yield new EntityItem($manager, $class, $id, $itemId);
        }
    }

    abstract protected function getClass(): string;

    abstract protected function getEntityIds(ObjectRepository $repository, \DateTimeImmutable $time): iterable;
}
