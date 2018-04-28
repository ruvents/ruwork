<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Handler;

use Ruwork\Synchronizer\ContextInterface;

final class DummyUpdater implements UpdaterInterface
{
    /**
     * {@inheritdoc}
     */
    public function update($source, $target, ContextInterface $context)
    {
        return $target;
    }
}
