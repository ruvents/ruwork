<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\NamingStrategy;

interface NamingStrategyInterface
{
    public function getLocalizedName(string $template, string $locale): string;
}
