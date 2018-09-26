<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Basket\Filter;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

final class OwnerRunetIdFilter extends AbstractBinaryFilter
{
    private $runetId;

    public function __construct(int $runetId, int $priority = 1)
    {
        $this->runetId = $runetId;
        parent::__construct($priority);
    }

    /**
     * {@inheritdoc}
     */
    protected function vote(ItemResult $item, ?OrderResult $order): bool
    {
        return $this->runetId === $item->Owner->RunetId;
    }
}
