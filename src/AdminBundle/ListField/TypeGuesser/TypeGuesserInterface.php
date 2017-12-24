<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\ListField\TypeGuesser;

interface TypeGuesserInterface
{
    public function guess(string $class, string $propertyPath): ?string;
}
