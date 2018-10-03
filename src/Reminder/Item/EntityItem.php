<?php

declare(strict_types=1);

namespace Ruwork\Reminder\Item;

use Doctrine\Common\Persistence\ObjectManager;

final class EntityItem implements ClearableItemInterface
{
    private $manager;
    private $class;
    private $entityId;
    private $id;
    private $initialized = false;
    private $entity;
    private $detach = true;

    public function __construct(
        ObjectManager $manager,
        string $class,
        $entityId,
        ?string $id = null
    ) {
        $this->manager = $manager;
        $this->class = $class;
        $this->entityId = $entityId;
        $this->id = $id ?? (string) $entityId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getData()
    {
        if (!$this->initialized) {
            $this->entity = $this->manager->find($this->class, $this->entityId);
            $this->initialized = true;
        }

        return $this->entity;
    }

    public function clear(): void
    {
        if ($this->detach && null !== $this->manager && null !== $this->entity) {
            $this->manager->detach($this->entity);
        }
    }

    public function setDetach(bool $detach): void
    {
        $this->detach = $detach;
    }
}
