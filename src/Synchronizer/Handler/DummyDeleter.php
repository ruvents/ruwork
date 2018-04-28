<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Handler;

use Ruwork\Synchronizer\ContextInterface;

final class DummyDeleter implements DeleterInterface
{
    /**
     * {@inheritdoc}
     */
    public function delete($target, ContextInterface $context): void
    {
    }
}
