<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Element;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

trait ProductElementTrait
{
    /**
     * @var null|ItemResult
     */
    protected $item;

    /**
     * @var null|OrderResult
     */
    protected $order;

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
