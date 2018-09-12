<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Data;

use RunetId\Client\Exception\RunetIdException;

abstract class AbstractElementData
{
    /**
     * @var null|RunetIdException
     */
    public $productException;

    /**
     * @var null|string
     */
    public $coupon;

    /**
     * @var null|RunetIdException
     */
    public $couponException;

    abstract public function getProductId(): ?int;
}
