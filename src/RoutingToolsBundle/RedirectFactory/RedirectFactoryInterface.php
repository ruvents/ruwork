<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\RedirectFactory;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface RedirectFactoryInterface
{
    public function create(
        string $url,
        int $status = Response::HTTP_TEMPORARY_REDIRECT,
        array $headers = []
    ): RedirectResponse;

    public function createForRoute(
        string $name,
        array $parameters = [],
        int $status = Response::HTTP_TEMPORARY_REDIRECT,
        array $headers = []
    ): RedirectResponse;
}
