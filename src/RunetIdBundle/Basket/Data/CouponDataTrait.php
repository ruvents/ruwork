<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket\Data;

use RunetId\Client\Exception\RunetIdException;

trait CouponDataTrait
{
    /**
     * @var null|string
     */
    private $coupon;

    /**
     * @var null|RunetIdException
     */
    private $couponException;

    public function getCoupon(): ?string
    {
        return $this->coupon;
    }

    public function setCoupon(?string $coupon)
    {
        $this->coupon = $coupon;
    }

    public function getCouponException(): ?RunetIdException
    {
        return $this->couponException;
    }

    public function setCouponException(?RunetIdException $couponException): void
    {
        $this->couponException = $couponException;
    }
}
