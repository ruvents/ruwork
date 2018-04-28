<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Handler;

use Ruwork\Synchronizer\ContextInterface;

final class DummyCreator implements CreatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($source, ContextInterface $context)
    {
        return null;
    }
}
