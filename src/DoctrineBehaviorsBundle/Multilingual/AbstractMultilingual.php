<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Multilingual;

abstract class AbstractMultilingual implements CurrentLocaleAwareInterface
{
    use CurrentLocaleAwareTrait;

    public function __construct()
    {
        $this->currentLocale = \Locale::getDefault();
    }

    public function getCurrent(bool $fallback = true)
    {
        $localesMap = array_flip($this->getLocales());
        $currentLocale = $this->currentLocale;

        if (isset($localesMap[$currentLocale])) {
            if ($this->$currentLocale || !$fallback) {
                return $this->$currentLocale;
            }

            unset($localesMap[$currentLocale]);
        }

        if (!$fallback) {
            return null;
        }

        foreach ($localesMap as $locale => $nb) {
            if ($this->$locale) {
                return $this->$locale;
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    abstract protected function getLocales(): array;
}
