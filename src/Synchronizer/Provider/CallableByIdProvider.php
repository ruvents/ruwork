<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\Provider;

final class CallableByIdProvider implements ByIdProviderInterface
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function getOneById($id)
    {
        return ($this->callable)($id);
    }
}
