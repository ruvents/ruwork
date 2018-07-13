<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Provider;

interface ProviderInterface
{
    public function getAll(): iterable;
}
