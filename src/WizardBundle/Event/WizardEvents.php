<?php

declare(strict_types=1);

namespace Ruwork\WizardBundle\Event;

final class WizardEvents
{
    /**
     * @Event("Ruwork\WizardBundle\Event\PreInitEvent")
     */
    const PRE_INIT = 'ruwork_wizard.pre_init';

    /**
     * @Event("Ruwork\WizardBundle\Event\WizardEvent")
     */
    const POST_INIT = 'ruwork_wizard.post_init';

    /**
     * @Event("Ruwork\WizardBundle\Event\WizardEvent")
     */
    const PRE_SAVE = 'ruwork_wizard.pre_save';

    /**
     * @Event("Ruwork\WizardBundle\Event\WizardEvent")
     */
    const POST_SAVE = 'ruwork_wizard.post_save';

    /**
     * @Event("Ruwork\WizardBundle\Event\WizardEvent")
     */
    const PRE_CLEAR = 'ruwork_wizard.pre_clear';

    /**
     * @Event("Ruwork\WizardBundle\Event\WizardEvent")
     */
    const POST_CLEAR = 'ruwork_wizard.post_clear';

    private function __construct()
    {
    }
}
