<?php

declare(strict_types=1);

namespace Ruwork\RoutingToolsBundle\Controller;

use Ruwork\RoutingToolsBundle\RedirectFactory\RedirectFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveTrailingSlashController
{
    private $redirectFactory;

    public function __construct(RedirectFactoryInterface $redirectFactory)
    {
        $this->redirectFactory = $redirectFactory;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $pathInfo = urldecode($request->getPathInfo());
        $pathInfo = rtrim($pathInfo, ' /');
        $query = $request->getQueryString();
        $uri = $request->getUriForPath($pathInfo).($query ? '?'.$query : '');

        return $this->redirectFactory->create($uri, RedirectResponse::HTTP_MOVED_PERMANENTLY);
    }
}
