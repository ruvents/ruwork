<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Handler;

use Ruwork\Synchronizer\ContextInterface;

interface UpdaterInterface
{
    /**
     * @return null|mixed
     */
    public function update($source, $target, ContextInterface $context);
}
