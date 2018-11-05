<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Exception;

class StepOutOfBoundsException extends \OutOfBoundsException implements ExceptionInterface
{
    public static function fromName(string $name): self
    {
        return new self(sprintf('Step "%s" does not exist in the wizard.', $name));
    }

    public static function fromIndex(int $index): self
    {
        return new self(sprintf('Step with index %d does not exist in the wizard.', $index));
    }
}
