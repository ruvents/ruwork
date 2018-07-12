<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Exception;

class NotMappedException extends \RuntimeException
{
    public function __construct(string $class, string $property, \Throwable $previous = null)
    {
        parent::__construct(\sprintf('Property %s of class %s is not mapped by Doctrine.', $property, $class), 0, $previous);
    }
}
