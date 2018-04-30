<?php

declare(strict_types=1);

namespace Ruwork\DoctrineBehaviorsBundle\Multilingual;

interface CurrentLocaleAwareInterface
{
    public function setCurrentLocale(string $locale): void;
}
