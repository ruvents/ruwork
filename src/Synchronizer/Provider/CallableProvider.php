<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Provider;

final class CallableProvider implements ProviderInterface
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): iterable
    {
        return ($this->callable)();
    }
}
