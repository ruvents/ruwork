<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Multilingual;

trait CurrentLocaleAwareTrait
{
    protected $currentLocale;

    /**
     * @see CurrentLocaleAwareInterface::setCurrentLocale()
     */
    public function setCurrentLocale(string $locale): void
    {
        $this->currentLocale = $locale;
    }
}
