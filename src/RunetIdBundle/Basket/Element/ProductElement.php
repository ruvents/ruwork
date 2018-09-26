<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Element;

use RunetId\Client\Result\Pay\ItemResult;
use RunetId\Client\Result\Pay\OrderResult;

class ProductElement
{
    use ProductElementTrait;

    public function __construct(?ItemResult $item = null, ?OrderResult $order = null)
    {
        $this->item = $item;
        $this->order = $order;
    }
}
