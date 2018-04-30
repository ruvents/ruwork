<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Multilingual;

trait MultilingualTrait
{
    protected $currentLocale;

    /**
     * @see CurrentLocaleAwareInterface::setCurrentLocale()
     */
    public function setCurrentLocale(string $locale): void
    {
        $this->currentLocale = $locale;
    }

    public function getCurrent(bool $fallback = true)
    {
        $localesMap = array_flip($this->getLocales());
        $currentLocale = $this->currentLocale ?? \Locale::getDefault();

        if (isset($localesMap[$currentLocale])) {
            $value = $this->getLocaleValue($currentLocale);

            if ($value || !$fallback) {
                return $value;
            }

            unset($localesMap[$currentLocale]);
        }

        if (!$fallback) {
            return null;
        }

        foreach ($localesMap as $locale => $nb) {
            if ($value = $this->getLocaleValue($locale)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    abstract protected function getLocales(): array;

    /**
     * @param string $locale
     *
     * @return mixed
     */
    abstract protected function getLocaleValue(string $locale);
}
