<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBasketBundle\Basket;

interface BasketFactoryInterface
{
    public function create(int $payerRunetId): Basket;
}
