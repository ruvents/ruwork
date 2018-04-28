<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Handler;

use Ruwork\Synchronizer\ContextInterface;

interface DeleterInterface
{
    /**
     * @param mixed            $target
     * @param ContextInterface $context
     */
    public function delete($target, ContextInterface $context): void;
}
