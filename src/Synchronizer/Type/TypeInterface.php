<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Type;

use Ruwork\Synchronizer\ContextInterface;

interface TypeInterface
{
    public function configure(ConfiguratorInterface $configurator, ContextInterface $context): void;
}
