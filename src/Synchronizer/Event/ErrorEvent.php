<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Event;

use Ruwork\Synchronizer\ContextInterface;

class ErrorEvent extends SyncEvent
{
    private $exception;
    private $ignored = false;

    public function __construct(
        ContextInterface $context,
        string $type,
        bool $root,
        $id,
        $source,
        $target,
        \Throwable $exception
    ) {
        parent::__construct($context, $type, $root, $id, $source, $target);
        $this->exception = $exception;
    }

    public function getException(): \Throwable
    {
        return $this->exception;
    }

    public function isIgnored(): bool
    {
        return $this->ignored;
    }

    public function ignore(): void
    {
        $this->ignored = true;
    }
}
