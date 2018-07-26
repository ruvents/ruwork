<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Basket;

interface HandlerInterface
{
    public function handle($object, Basket $basket): void;

    public function supportsHandling($object): bool;
}
