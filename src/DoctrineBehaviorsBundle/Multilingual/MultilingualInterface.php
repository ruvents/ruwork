<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Multilingual;

interface MultilingualInterface
{
    public function setCurrentLocale(string $locale): void;
}
