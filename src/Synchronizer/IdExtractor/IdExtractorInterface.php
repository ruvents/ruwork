<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\IdExtractor;

interface IdExtractorInterface
{
    /**
     * @param mixed $item
     *
     * @return int|float|string
     */
    public function extractId($item);
}
