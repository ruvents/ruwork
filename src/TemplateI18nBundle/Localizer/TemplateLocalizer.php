<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Localizer;

use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolverInterface;
use Twig\Environment;

final class TemplateLocalizer
{
    private $twig;
    private $resolver;

    public function __construct(
        Environment $twig,
        LocalizedTemplateResolverInterface $resolver
    ) {
        $this->twig = $twig;
        $this->resolver = $resolver;
    }

    public function getName(string $template, iterable $locales = []): string
    {
        return $this->resolver->resolve($template, $locales);
    }

    public function load(string $template, iterable $locales = []): \Twig_TemplateWrapper
    {
        return $this->twig->load($this->getName($template, $locales));
    }
}
