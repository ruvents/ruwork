<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Security;

final class TokenGenerator
{
    private function __construct()
    {
    }

    public static function generate(int $length): string
    {
        return \bin2hex(\random_bytes($length / 2));
    }
}
