<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\IdExtractor;

interface IdExtractorInterface
{
    /**
     * @param mixed $item
     *
     * @return float|int|string
     */
    public function extractId($item);
}
