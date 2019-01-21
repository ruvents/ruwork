<?php

declare(strict_types=1);

namespace Ruwork\Paginator\Exception;

class PageOutOfRangeException extends \OutOfRangeException
{
    public function __construct(int $total, int $current, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Page %d is out of range [1, %d].', $current, $total), $code, $previous);
    }
}
