<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Exception;

class EmptyWizardException extends RuntimeException
{
    public static function create(string $message = 'Wizard is empty.'): self
    {
        return new self($message);
    }
}
