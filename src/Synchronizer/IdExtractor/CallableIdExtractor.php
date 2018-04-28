<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\IdExtractor;

final class CallableIdExtractor implements IdExtractorInterface
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function extractId($item)
    {
        return ($this->callable)($item);
    }
}
