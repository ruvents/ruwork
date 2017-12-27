<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle\Event;

use Ruwork\WizardBundle\Wizard;
use Symfony\Component\EventDispatcher\Event;

class WizardEvent extends Event
{
    private $wizard;

    public function __construct(Wizard $wizard)
    {
        $this->wizard = $wizard;
    }

    public function getWizard(): Wizard
    {
        return $this->wizard;
    }
}
