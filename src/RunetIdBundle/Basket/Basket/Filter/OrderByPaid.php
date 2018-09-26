<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket\Filter;

use RunetId\Client\Result\Pay\ItemResult;

final class OrderByPaid
{
    private $paidPriority;
    private $notPaidPriority;

    public function __construct(int $paidPriority = 1, int $notPaidPriority = 0)
    {
        $this->paidPriority = $paidPriority;
        $this->notPaidPriority = $notPaidPriority;
    }

    public function __invoke(ItemResult $item): int
    {
        return $item->Paid ? $this->paidPriority : $this->notPaidPriority;
    }
}
