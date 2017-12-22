<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Multilingual;

interface MultilingualInterface
{
    public function setCurrentLocale(string $locale);

    public function has(string $locale): bool;

    public function get(string $locale);

    public function getCurrent();
}
