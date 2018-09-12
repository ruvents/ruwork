<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Element;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

abstract class AbstractElement
{
    private $item;
    private $order;

    public function __construct(?ItemResult $item = null, ?OrderResult $order = null)
    {
        $this->item = $item;
        $this->order = $order;
    }

    abstract public function getRunetId(): int;

    final public function isAdded(): bool
    {
        return null !== $this->item;
    }

    final public function getItem(): ?ItemResult
    {
        return $this->item;
    }

    final public function isOrdered(): bool
    {
        return null !== $this->order;
    }

    final public function getOrder(): ?OrderResult
    {
        return $this->order;
    }

    final public function isPaid(): bool
    {
        return $this->isAdded() && $this->item->Paid;
    }

    public function isLocked(): bool
    {
        return $this->isPaid() || $this->isOrdered();
    }

    final public function getTotal(): int
    {
        if (!$this->isAdded() || $this->isLocked()) {
            return 0;
        }

        return $this->item->PriceDiscount;
    }
}
