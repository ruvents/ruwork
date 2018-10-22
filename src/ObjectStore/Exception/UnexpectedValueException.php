<?php

declare(strict_types=1);

namespace Ruwork\ObjectStore\Exception;

class UnexpectedValueException extends \UnexpectedValueException implements ExceptionInterface
{
    public static function createForValue($value, string $expectedType): self
    {
        return new self(\sprintf(
            'Expected "%s", "%s" given.',
            $expectedType,
            \is_object($value) ? \get_class($value) : \gettype($value)
        ));
    }
}
