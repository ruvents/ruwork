<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBasketBundle\Handler;

use Ruwork\RunetIdBasketBundle\Basket\Basket;

interface HandlerInterface
{
    public function handle($object, Basket $basket): void;

    public function supportsHandling($object): bool;
}
