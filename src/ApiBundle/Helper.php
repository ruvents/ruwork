<?php

namespace Ruwork\ApiBundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class Helper
{
    const RUWORK_API = '_ruwork_api';

    private function __construct()
    {
    }

    public static function isApiRoute(Route $route): bool
    {
        return true === $route->getDefault(self::RUWORK_API);
    }

    public static function isApiRequest(Request $request): bool
    {
        return $request->attributes->getBoolean(self::RUWORK_API);
    }
}
