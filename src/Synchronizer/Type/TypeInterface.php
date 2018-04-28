<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Type;

interface TypeInterface
{
    public function configure(ConfiguratorInterface $configurator): void;
}
