<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\RedirectFactory;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface RedirectFactoryInterface
{
    public function create(
        string $url,
        int $status = RedirectResponse::HTTP_FOUND,
        array $headers = []
    ): RedirectResponse;

    public function createForRoute(
        string $name,
        array $parameters = [],
        int $status = RedirectResponse::HTTP_FOUND,
        array $headers = []
    ): RedirectResponse;
}
