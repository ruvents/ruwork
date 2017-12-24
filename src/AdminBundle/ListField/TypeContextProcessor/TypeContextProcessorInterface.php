<?php

declare(strict_types=1);

namespace Ruwork\AdminBundle\ListField\TypeContextProcessor;

interface TypeContextProcessorInterface
{
    public static function getType(): string;

    public function process(string $class, ?string $propertyPath, array &$context);
}
