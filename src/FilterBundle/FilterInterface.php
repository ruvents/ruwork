<?php

declare(strict_types=1);

namespace Ruwork\FilterBundle;

use Symfony\Component\HttpFoundation\Request;

interface FilterInterface
{
    public function apply($object, Request $request): FilterResultInterface;
}
