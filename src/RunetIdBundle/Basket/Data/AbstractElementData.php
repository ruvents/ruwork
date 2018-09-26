<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Data;

use RunetId\Client\Exception\RunetIdException;

/**
 * @deprecated since 0.12.5 and will be removed in 0.13
 */
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
