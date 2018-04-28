<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Event;

use Ruwork\Synchronizer\ContextInterface;
use Symfony\Component\EventDispatcher\Event;

class CompleteEvent extends Event
{
    private $context;
    private $type;

    public function __construct(ContextInterface $context, string $type)
    {
        $this->context = $context;
        $this->type = $type;
    }

    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
