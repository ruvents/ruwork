<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Event;

use Ruwork\Synchronizer\ContextInterface;
use Symfony\Component\EventDispatcher\Event;

class SyncEvent extends Event
{
    private $context;
    private $type;
    private $root;
    private $id;
    private $source;
    private $target;

    public function __construct(
        ContextInterface $context,
        string $type,
        bool $root,
        $id,
        $source,
        $target
    ) {
        $this->context = $context;
        $this->type = $type;
        $this->root = $root;
        $this->id = $id;
        $this->source = $source;
        $this->target = $target;
    }

    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isRoot(): bool
    {
        return $this->root;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getTarget()
    {
        return $this->target;
    }
}
