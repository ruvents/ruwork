<?php

declare(strict_types=1);

namespace Ruwork\Wizard\Exception;

use Ruwork\Wizard\Step\StepInterface;

class InvalidPrecedingStepException extends RuntimeException
{
    private $step;

    public function __construct(
        StepInterface $step,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null)
    {
        $this->step = $step;
        parent::__construct($message, $code, $previous);
    }

    public static function fromStep(StepInterface $step): self
    {
        return new self($step, sprintf('Preceding step "%s" is invalid.', $step->getName()));
    }

    public function getStep(): StepInterface
    {
        return $this->step;
    }
}
