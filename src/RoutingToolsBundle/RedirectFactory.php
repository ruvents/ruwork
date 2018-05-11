<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectFactory
{
    private $urlGenerator;
    private $requestStack;

    public function __construct(UrlGeneratorInterface $urlGenerator, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    public function url(
        string $url,
        int $status = RedirectResponse::HTTP_FOUND,
        array $headers = []
    ): RedirectResponse {
        return new RedirectResponse($url, $status, $headers);
    }

    public function route(
        string $name,
        array $parameters = [],
        int $status = RedirectResponse::HTTP_FOUND,
        array $headers = []
    ): RedirectResponse {
        $url = $this->urlGenerator->generate($name, $parameters);

        return $this->url($url, $status, $headers);
    }

    public function current(
        int $status = RedirectResponse::HTTP_FOUND,
        array $headers = []
    ): RedirectResponse {
        $url = $this->requestStack->getMasterRequest()->getRequestUri();

        return $this->url($url, $status, $headers);
    }
}
