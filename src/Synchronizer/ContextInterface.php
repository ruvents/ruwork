<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

interface ContextInterface
{
    public function getSynchronizer(string $type): SynchronizerInterface;

    /**
     * @param null|mixed $default
     */
    public function getAttribute(string $name, $default = null);

    public function setAttribute(string $name, $value): void;
}
