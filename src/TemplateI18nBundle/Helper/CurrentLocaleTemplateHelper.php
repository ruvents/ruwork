<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Helper;

use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class CurrentLocaleTemplateHelper
{
    private $twig;
    private $resolver;
    private $requestStack;

    public function __construct(
        Environment $twig,
        LocalizedTemplateResolverInterface $resolver,
        RequestStack $requestStack
    ) {
        $this->twig = $twig;
        $this->resolver = $resolver;
        $this->requestStack = $requestStack;
    }

    public function getName(string $template): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return $this->resolver->resolve($template);
        }

        return $this->resolver->resolve($template, [$request->getLocale()]);
    }

    public function load(string $template): \Twig_TemplateWrapper
    {
        return $this->twig->load($this->getName($template));
    }
}
