<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket\Filter;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

abstract class AbstractBinaryFilter
{
    private $priority;

    public function __construct(int $priority = 0)
    {
        $this->priority = $priority;
    }

    public function __invoke(ItemResult $item, ?OrderResult $order): ?int
    {
        return $this->vote($item, $order) ? $this->priority : null;
    }

    abstract protected function vote(ItemResult $item, ?OrderResult $order): bool;
}
