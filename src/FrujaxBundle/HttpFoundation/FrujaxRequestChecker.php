<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Request;

final class FrujaxRequestChecker
{
    public static function isFrujaxRequest(Request $request)
    {
        return $request->isXmlHttpRequest() && $request->headers->has(FrujaxHeaders::FRUJAX);
    }
}
