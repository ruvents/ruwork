<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Resolver;

use Twig\Template;

interface LocalizedTemplateResolverInterface
{
    public function resolve(string $name, array $locales): Template;
}
