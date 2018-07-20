<?php

declare(strict_types=1);

namespace Ruwork\TemplateI18nBundle\Controller;

use Ruwork\TemplateI18nBundle\Resolver\LocalizedTemplateResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TemplateI18nController
{
    private $twig;
    private $resolver;

    public function __construct(Environment $twig, LocalizedTemplateResolverInterface $resolver)
    {
        $this->twig = $twig;
        $this->resolver = $resolver;
    }

    public function __invoke(
        string $_locale,
        string $template,
        int $maxAge = null,
        int $sharedAge = null,
        bool $private = null
    ): Response {
        $template = $this->resolver->resolve($template, [$_locale]);
        $response = new Response($this->twig->render($template));

        if ($maxAge) {
            $response->setMaxAge($maxAge);
        }

        if ($sharedAge) {
            $response->setSharedMaxAge($sharedAge);
        }

        if ($private) {
            $response->setPrivate();
        } elseif (false === $private || (null === $private && ($maxAge || $sharedAge))) {
            $response->setPublic();
        }

        return $response;
    }
}
