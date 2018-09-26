<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Data;

class CouponData
{
    use CouponDataTrait;

    public function __construct(?string $coupon = null)
    {
        $this->setCoupon($coupon);
    }
}
