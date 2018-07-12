<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle\Exception;

final class NotFoundException extends \RuntimeException
{
    public function __construct(string $name, \Throwable $previous = null)
    {
        parent::__construct(\sprintf('Feature "%s" does not exist.', $name), 0, $previous);
    }
}
