<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Provider;

interface ProviderInterface
{
    /**
     * @return iterable
     */
    public function getAll(): iterable;
}
