<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket\Filter;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

final class CompositeFilter
{
    private $filters;

    public function __construct(callable ...$filters)
    {
        $this->filters = $filters;
    }

    public function __invoke(ItemResult $item, ?OrderResult $order): ?int
    {
        $max = null;

        foreach ($this->filters as $filter) {
            $result = $filter($item, $order);

            if (null === $result) {
                return null;
            }

            if (null === $max || $result > $max) {
                $max = $result;
            }
        }

        return $max ?? 1;
    }
}
