<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\NamingStrategy;

final class NamingStrategy implements NamingStrategyInterface
{
    private $pattern;
    private $noSuffixLocale;

    public function __construct(
        string $localeSuffixPattern,
        string $extensionPattern = '\.\w+\.twig',
        string $noSuffixLocale = null
    ) {
        $this->pattern = sprintf('#(\.(%s))?(%s)$#', $localeSuffixPattern, $extensionPattern);
        $this->noSuffixLocale = $noSuffixLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocalizedName(string $name, string $locale): string
    {
        $replacement = $locale === $this->noSuffixLocale ? '' : '.'.preg_quote($locale, '#');

        return preg_replace($this->pattern, $replacement.'$3', $name);
    }
}
