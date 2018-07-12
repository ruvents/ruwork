<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle\Exception;

class StepNotFoundException extends \OutOfBoundsException
{
    public function __construct($step)
    {
        parent::__construct(\sprintf('Step "%s" was not found in the wizard.', $step));
    }
}
