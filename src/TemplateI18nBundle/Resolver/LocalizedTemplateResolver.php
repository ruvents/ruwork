<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Resolver;

use Ruwork\TemplateI18nBundle\NamingStrategy\NamingStrategyInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class LocalizedTemplateResolver implements LocalizedTemplateResolverInterface
{
    private $namingStrategy;
    private $twig;
    private $requestStack;
    private $defaultLocale;

    public function __construct(
        NamingStrategyInterface $namingStrategy,
        Environment $twig,
        RequestStack $requestStack,
        string $defaultLocale
    ) {
        $this->namingStrategy = $namingStrategy;
        $this->twig = $twig;
        $this->requestStack = $requestStack;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(string $template): string
    {
        $templates = [];

        if (null !== $request = $this->requestStack->getCurrentRequest()) {
            $templates[] = $this->namingStrategy->getLocalizedName($template, $request->getLocale());
        }

        $templates[] = $this->namingStrategy->getLocalizedName($template, $this->defaultLocale);
        $templates[] = $template;

        /* @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->twig->resolveTemplate($templates)->getTemplateName();
    }
}
