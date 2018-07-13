<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Handler;

use Ruwork\Synchronizer\ContextInterface;

interface CreatorInterface
{
    /**
     * @return null|mixed
     */
    public function create($source, ContextInterface $context);
}
