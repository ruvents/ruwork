<?php

namespace Ruvents\RuworkBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RemoveTrailingSlashController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $pathInfo = rtrim(urldecode($request->getPathInfo()), ' /');
        $query = $request->getQueryString();

        $uri = $request->getUriForPath($pathInfo).($query ? '?'.$query : '');

        return new RedirectResponse($uri, RedirectResponse::HTTP_MOVED_PERMANENTLY);
    }
}
