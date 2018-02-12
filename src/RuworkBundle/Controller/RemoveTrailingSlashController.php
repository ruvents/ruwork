<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class RemoveTrailingSlashController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $pathInfo = rtrim(urldecode($request->getPathInfo()), ' /');
        $query = $request->getQueryString();

        $uri = $request->getUriForPath($pathInfo).($query ? '?'.$query : '');

        return new RedirectResponse($uri, RedirectResponse::HTTP_MOVED_PERMANENTLY);
    }
}
