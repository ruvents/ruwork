<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Resolver;

interface LocalizedTemplateResolverInterface
{
    public function resolve(string $template): string;
}
