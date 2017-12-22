<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Multilingual;

abstract class AbstractMultilingual implements MultilingualInterface
{
    private $currentLocale;

    public function has(string $locale): bool
    {
        return property_exists($this, $locale);
    }

    public function get(string $locale)
    {
        if (!$this->has($locale)) {
            throw new \OutOfBoundsException(sprintf('@Multilingual "%s" does not support locale "%s".', get_class($this), $locale));
        }

        return $this->$locale;
    }

    public function set(string $locale, $value)
    {
        if (!$this->has($locale)) {
            throw new \OutOfBoundsException(sprintf('@Multilingual "%s" does not support locale "%s".', get_class($this), $locale));
        }

        $this->$locale = $value;

        return $this;
    }

    public function setCurrentLocale(string $locale)
    {
        if ($this->has($locale)) {
            $this->currentLocale = $locale;
        }

        return $this;
    }

    public function getCurrent(bool $fallback = true)
    {
        $currentLocale = $this->getCurrentLocale();

        if ($current = $this->get($currentLocale)) {
            return $current;
        }

        if ($fallback) {
            foreach ($this->getFallbackLocales() as $locale) {
                if ($current = $this->get($locale)) {
                    break;
                }
            }
        }

        return $current;
    }

    public function __toString(): string
    {
        return (string) $this->getCurrent();
    }

    protected function getCurrentLocale(): string
    {
        return $this->currentLocale ?? $this->getFallbackLocales()->current();
    }

    /**
     * @return \Generator|string[]
     */
    abstract protected function getFallbackLocales(): \Generator;
}
