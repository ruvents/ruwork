<?php

declare(strict_types=1);

namespace Ruwork\Synchronizer\IdExtractor;

interface IdExtractorInterface
{
    /**
     * @return float|int|string
     */
    public function extractId($item);
}
