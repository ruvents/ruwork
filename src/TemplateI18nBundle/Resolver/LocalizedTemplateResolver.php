<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Resolver;

use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategyInterface;
use Twig\Environment;

final class LocalizedTemplateResolver implements LocalizedTemplateResolverInterface
{
    private $twig;
    private $namingStrategy;
    private $defaultLocale;

    public function __construct(
        Environment $twig,
        NamingStrategyInterface $namingStrategy,
        ?string $defaultLocale = null
    ) {
        $this->twig = $twig;
        $this->namingStrategy = $namingStrategy;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(string $template, iterable $locales = []): string
    {
        $failed = [];

        foreach ($this->getTemplates($template, $locales) as $template) {
            if (isset($failed[$template])) {
                continue;
            }

            try {
                return $this->twig->resolveTemplate($template)->getTemplateName();
            } catch (\Twig_Error_Loader $exception) {
                $failed[$template] = true;
            }
        }

        if (1 === \count($failed)) {
            throw $exception;
        }

        throw new \Twig_Error_Loader(sprintf(
            'Failed to find any of the following templates: "%s".',
            implode('", "', array_keys($failed))
        ));
    }

    /**
     * @return string[]
     */
    private function getTemplates(string $template, iterable $locales): \Generator
    {
        foreach ($locales as $locale) {
            yield $this->namingStrategy->getLocalizedName($template, $locale);
        }

        if (null !== $this->defaultLocale) {
            yield $this->namingStrategy->getLocalizedName($template, $this->defaultLocale);
        }

        yield $template;
    }
}
