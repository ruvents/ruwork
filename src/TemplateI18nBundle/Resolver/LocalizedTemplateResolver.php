<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Resolver;

use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategyInterface;
use Twig\Environment;
use Twig\Template;

final class LocalizedTemplateResolver implements LocalizedTemplateResolverInterface
{
    private $strategy;
    private $twig;

    public function __construct(NamingStrategyInterface $strategy, Environment $twig)
    {
        $this->strategy = $strategy;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(string $name, array $locales): Template
    {
        $names = [];

        foreach ($locales as $locale) {
            $names[] = $this->strategy->getLocalizedName($name, $locale);
        }

        $names[] = $name;

        /* @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->twig->resolveTemplate($names);
    }
}
