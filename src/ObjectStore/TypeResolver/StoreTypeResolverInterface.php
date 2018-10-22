<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\TypeResolver;

interface StoreTypeResolverInterface
{
    public function resolve(string $type): ResolvedStoreTypeInterface;
}
