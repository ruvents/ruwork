<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer;

interface ContextInterface
{
    public function getSynchronizer(string $type): SynchronizerInterface;

    /**
     * @param string     $name
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function getAttribute(string $name, $default = null);

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function setAttribute(string $name, $value): void;
}
