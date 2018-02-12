<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\RedirectFactory;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RedirectFactory implements RedirectFactoryInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function create(
        string $url,
        int $status = Response::HTTP_TEMPORARY_REDIRECT,
        array $headers = []
    ): RedirectResponse {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function createForRoute(
        string $name,
        array $parameters = [],
        int $status = Response::HTTP_TEMPORARY_REDIRECT,
        array $headers = []
    ): RedirectResponse {
        $url = $this->urlGenerator->generate($name, $parameters);

        return $this->create($url, $status, $headers);
    }
}
